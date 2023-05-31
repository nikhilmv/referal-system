<?php

namespace App\Http\Controllers;
use File;
use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\ShortLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\ProductRequest;
use Symfony\Component\Console\Input\Input;
use PDF;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        $data = [
            'count_user' => Order::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_order',
            'title'    => 'Table Order'
        ];

        if ($request->ajax()) {
            $order = Order::orderByDesc('created_at');
            return Datatables::of($order)
                    ->addIndexColumn()

                        ->editColumn('created_date', function ($row) {

                            return  Carbon::parse($row->created_at)->format('Y-m-d h:i:s');
                        })
                    ->addColumn('action', function($row){

                        $btn = '<div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-icon btn-outline-success btn-circle mr-2 edit editUser"><i class=" fi-rr-edit"></i></div>';
                        $btn = $btn.' <div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-icon btn-outline-danger btn-circle mr-2 deleteUser"><i class="fi-rr-trash"></i></div>';

                    //     $btn = $btn.' <div class="btn btn-sm btn-icon btn-outline-success btn-circle mr-2 invoiceDownload" id='.$row->phone_no.' data-link="'.route('shorten.link', $row->id).'">
                    //     <i class="fi-rr-download"></i>
                    //   </div>';

                      $btn = $btn.'<div  class="btn btn-sm btn-icon btn-outline-success btn-circle mr-2 invoiceDownload" data-toggle="modal" data-target="#danger-alert-modal" onclick="pdfGen(\'invgen'.$row->id.'\')" ><i class="fi-rr-download"></i></div><form id="invgen'.$row->id.'" method="post" action="'.route("order.invoice", $row->phone_no).'"><input type="hidden" name="_method" value="post"><input type="hidden" name="_token"  value="'.csrf_token().'"></form>';

                         return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('layouts.v_template',$data);
    }

    public function create()
    {
        $data = [
            'count_user' => Order::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_order_create',
            'title'    => 'Table Order create',
            'categories' => Category::all(),
            'products' => Product::all(),

        ];

        return view('layouts.v_template',$data);



    }
    public function store(OrderRequest $request)
    {
       
    // DB::beginTransaction();
    // try {

        $request_data=[];
        $request_data['customer_name']=$request->customer_name;
        $request_data['phone_no']=$request->phone_no;
 
        
        foreach ($request->product_id as $key=>$product_id) { 
            $getProductName = Product::where('id',$product_id)->select('product_name','price')->first(); 
            $price = $getProductName->price;
            $totalPrice =   $price*($request->quantity[$key]);
            $data[] = [ 
                "product_id" => $request->product_id[$key],
                "product_name"  => $getProductName->product_name,  
                "quantity"  => $request->quantity[$key], 
                "total"  => $totalPrice,  

            ];
        } 
 
           $request_data['product_id'] =json_encode($data);
           $request_data['amount'] = 0;
           $totalPrice=0;
           foreach ($data as $key => $d) {
            $getProduct = Product::where('id',$d['product_id'])->pluck('price')->first();
            $totalPrice =  $totalPrice+$getProduct*($d['quantity']);
           }
           $request_data['amount'] =$totalPrice; 
            // Create new admin
            $data = Order::create($request_data);
            DB::commit();

            if ($data) {
                return response()->json(['success'=>'Order added Successfully!']);
             } else {
                return response()->json(['error'=>'There is some error.please try again !']);
            }

    //     } catch (\Exception $e) {
    //     DB::rollback();
    //     return response()->json(['error'=>'There is some error.please try again !']);

    // }


        // return response()->json(['success'=>'Shorten Link Generated Successfully!']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateInvoice($data)
    {
       $orders =  Order::with('product')->where('phone_no',$data)->first(); 
        // dd($orders);
            // share data to view
            view()->share('invoiceExport',$orders);
            $pdf = PDF::loadView('myPDF', $orders->toArray());
            $dir = public_path("storage/product/images");
            $fileName =  time().'.'. 'pdf' ;
            $pdf->save($dir . '/' . $fileName);
            // download PDF file with download method
            return $pdf->download('invoice.pdf');
    }
    /**
     * Delete a resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ShortLink::find($id)->delete();

        return response()->json(['success'=>'Link deleted!']);
    }

}
