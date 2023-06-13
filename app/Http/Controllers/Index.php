<?php

namespace App\Http\Controllers;

use App\Models\CityModel;
use App\Models\CountryModel;
use App\Models\RegisterModel;
use App\Models\StateModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;

class Index extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function login_page()
    {
        return view('login_page');
    }

    public function register(Request $request)
    {
        
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:registration',
            'password' => 'required',
        ]);
        
        if($validation->fails())
        {
            return response()->json(['code' => 401, 'msg' => $validation->errors()->toArray()]);
        }
        else{
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $resp = RegisterModel::insert($data);
            if($resp)
            {
                return response()->json(['code' => 200, 'msg' => 'Success']);
            }else{
                return response()->json(['code' => 400, 'msg' => 'Try Again.']);
            }
        }

    }

    public function user_login(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);
        if($validation->fails())
        {
            return response()->json(['code' => 401, 'msg' => $validation->errors()->toArray()]);
        }else{
            $check = RegisterModel::where('email',$request->email)->where('flag','1')->first();
            if($check)
            {
                $pass_verify = Hash::check($request->password,$check->password);
                if($pass_verify)
                {
                    $session['user'] = ['id'=>$check->id,'name'=>$check->name,'email'=>$check->email];
                    Session::put($session);
                    Cookie::queue('user_email',$check->email,time()+60*60*24*100);
                    Cookie::queue('user_password',$check->password,time()+60*60*24*100);

                    return response()->json(['code' => 200, 'msg' => 'Login Success']); 
                }else{
                    return response()->json(['code' => 201, 'msg' => 'Incorrect Password']); 
                }
            }else{
                return response()->json(['code' => 201, 'msg' => 'Email Not Registered']); 
            }
        }
    }

    public function task_2()
    {
        $data['country'] = CountryModel::all();
        return view('task_2',$data);
    }

    public function get_state(Request $request)
    {
        $id = $request->id;
        $state = StateModel::where('country_id',$id)->get();
        $output = '<option value="" selected>Select State</option>';
        foreach($state as $val)
        {
            $output .='<option value="'.$val->id.'">'.$val->name.'</option>';
        }
        return response()->json(['code' => 201, 'output' => $output]); 
    }

    public function get_city(Request $request)
    {
        $id = $request->id;
        $state = CityModel::where('state_id',$id)->get();
        $output = '<option value="" selected>Select City</option>';
        foreach($state as $val)
        {
            $output .='<option value="'.$val->id.'">'.$val->name.'</option>';
        }
        return response()->json(['code' => 201, 'output' => $output]); 
    }
}
