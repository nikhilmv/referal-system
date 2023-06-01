<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'level' => ['string'],

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $defaultPoint = 10;//all users will get a point of 10 when register
        if($data['referal_code'] !=''){
            $existingReferalCode = User::where('refereal_code', $data['referal_code'])->first();
            $parentId = $existingReferalCode->id;

        }else{
            $parentId = null;
        }
 
       $code =  $this->generateUniqueCode();
       $data =  User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'level'          => '1',
            'refereal_code'  => $code,
            'parent_user_id' => $parentId,
            'points'         => $defaultPoint,
            'password'       => Hash::make($data['password']),
        ]);
 
        if(!isset($existingReferalCode->id)){
            $ref = $data->refereal_code; 
        } else {
            $ref = $existingReferalCode->id; 
        }
        
        $alluser = User::whereBetween('id', [0, $ref])
        ->orderBy('id','DESC')->get();
        
        $arr =[]; 
        foreach ($alluser as $key => $user) {
            $currentRec = User::where('refereal_code', $user->refereal_code)->first();  
            if (isset($currentRec->parent_user_id)) {
                $parent_rec = User::where('id', $currentRec->parent_user_id)->first(); 
                array_push($arr, $parent_rec->id);
                array_push($arr, $currentRec->id);

            } else{
                array_push($arr, $currentRec->id); 
                break;
            } 
        }
        $filteredArr = array_unique($arr);
        arsort($filteredArr);
        $i=1;
        foreach ($filteredArr as $key => $filtArr) { 
         
            $rec = User::find($filtArr);
            $rec->points = $rec->points+$defaultPoint-$i;
            $rec->save();
 
        $i++;  
        }
        
        exit();

    }



    public function generateUniqueCode()
    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (User::where('refereal_code', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;

    }


}
