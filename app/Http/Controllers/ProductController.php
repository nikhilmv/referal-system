<?php

namespace App\Http\Controllers;
use File;
use DataTables;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ShortLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;
use Symfony\Component\Console\Input\Input;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $data = [
            'count_user' => Product::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_product',
            'title'    => 'Table Product'
        ];

        if ($request->ajax()) {
            $q_user = Product::orderByDesc('created_at');
            return Datatables::of($q_user)
                    ->addIndexColumn()
                    ->editColumn('category_id', function ($row) {

                        return $row->category->category_name;
                    })
                    ->addColumn('action', function($row){

                        $btn = '<div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-icon btn-outline-success btn-circle mr-2 edit editUser"><i class=" fi-rr-edit"></i></div>';
                        $btn = $btn.' <div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-icon btn-outline-danger btn-circle mr-2 deleteUser"><i class="fi-rr-trash"></i></div>';

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
            'count_user' => Product::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_product_create',
            'title'    => 'Table Product create',
            'categories' => Category::all(),

        ];

        return view('layouts.v_template',$data);



    }
    public function store(ProductRequest $request)
    {

    DB::beginTransaction();
    try {

        $request_data=[];
        $request_data['product_name']=$request->product_name;
        $request_data['category_id']=$request->category_id;
        $request_data['price'] =$request->price;


            if($request->hasFile('image')) {
                $filename = time() . '.' . $request->image->extension();
                $dir = public_path("storage/product/images");
                if (!File::isDirectory($dir)) {
                    \File::makeDirectory($dir, 493, true);
                }

            //move file to public/images dir
            $request->image->move( $dir , $filename);
            $request_data['product_image'] = $filename;

            }else{
                $request_data['product_image'] = "";
            }

            // Create new admin
            $data = Product::create($request_data);
            DB::commit();

            if ($data) {
                return response()->json(['success'=>'Product added Successfully!']);
             } else {
                return response()->json(['error'=>'There is some error.please try again !']);
            }

        } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['error'=>'There is some error.please try again !']);

    }


        // return response()->json(['success'=>'Shorten Link Generated Successfully!']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shortenLink($code)
    {
        $find = ShortLink::where('code', $code)->first();

        return redirect($find->link);
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
