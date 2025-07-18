<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    function registration () {
        return view('front.account.registration');
    }

    function submitRegistration (Request $request) {
        $validator = Validator::make($request->all(),
            [
            'name'=>'required|unique:users,name',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:5|same:confirm_password',
            'confirm_password'=>'required',
            ]
            // ,[
            // 'name.required'=>'กรุณาระบุข้อมูล', 
            // 'email.required'=>'กรุณาระบุข้อมูล',    
            // 'password.required'=>'กรุณาระบุข้อมูล',    
            // 'confirm_password.required'=>'กรุณาระบุข้อมูล',  
            // ]
        );


        if ($validator->passes()) {
            // $user = new User();
            // $user->name = $request->name;
            // $user->email = $request->email;
            // $user->password = Hash::make($request->password);
            // $user->save();

            $data = [
            'name' =>$request->name,
            'email' =>$request->email,
            'password' =>Hash::make($request->password),
            ];

            User::create($data);

            session()->flash('success', 'You have registered successfully.');

            return response()->json([
                'status' => true,
                'errors' => [],
            ]);
           
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }


    }

    function login () {
        return view('front.account.login');
    }

}
