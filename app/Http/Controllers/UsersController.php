<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    } 

    public function index(Request $request)
    {
        
        $data = [
            'count_user' => User::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_user',
            'title'    => 'Table User'
        ];

        if ($request->ajax()) {
            $user = auth()->user(); 
            if ($user->level == 1) {
                $q_user = User::select('*')->orderByDesc('created_at'); 
            }else{
                $q_user = User::select('*')->where('id','=', $user->id)->orderByDesc('created_at');

            }
            return Datatables::of($q_user)
                    ->addIndexColumn()
                    ->editColumn('refereal_code', function ($row) {
                        return isset($row->refereal_code)?$row->refereal_code:'null';
                     })
                    ->editColumn('points', function ($row) {
                        return isset($row->points)?$row->points:'null';
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
        //
    }

    public function store(Request $request)
    {
 
        User::updateOrCreate(['id' => $request->user_id],
                [
                 'name' => $request->name,
                 'email' => $request->email,
                 'level' => $request->level,
                 'password' => Hash::make($request->password),
                ]);        

        return response()->json(['success'=>'User saved successfully!']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $User = User::find($id);
        return response()->json($User);

    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        User::find($id)->delete();

        return response()->json(['success'=>'Customer deleted!']);
    }


   

}
