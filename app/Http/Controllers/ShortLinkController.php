<?php

namespace App\Http\Controllers;
use DataTables;
use App\Models\ShortLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ShortLinkController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'count_user' => ShortLink::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.shortlink',
            'title'    => 'List URLs'
        ];

        if ($request->ajax()) {
            $q_user = ShortLink::orderByDesc('created_at');
            return Datatables::of($q_user)
                    ->addIndexColumn()
                    ->editColumn('code', function ($row) {
                        return '<a href="'.route('shorten.link', $row->code) .'">'. route('shorten.link', $row->code) .'</a>';
                     })
                    ->editColumn('created_date', function ($row) {
                        return date("d-m-Y", strtotime($row->created_at));
                     })
                    ->addColumn('action', function($row){

                        $btn = ' <button class="btn btn-primary clipboard" id=btn'.$row->id.' data-link="'.route('shorten.link', $row->code).'">
                        Copy
                      </button>';
                         return $btn;

                    })
                    ->rawColumns(['action','code'])
                    ->make(true);
        }

        return view('layouts.v_template',$data);
    }

    public function store(Request $request)
    {

        $request->validate([
           'link' => 'required|url' ,
            'title' => 'required',

        ]);

        $input['link'] = $request->link;
        $input['code'] = Str::random(6);
        $input['title'] = $request->title;

        ShortLink::create($input);
        return response()->json(['success'=>'Shorten Link Generated Successfully!']);

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
