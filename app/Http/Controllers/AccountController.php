<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    function registration()
    {
        return view('front.account.registration');
    }

    function submitRegistration(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:5|max:20|unique:users,name',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:5|same:confirm_password',
                'confirm_password' => 'required',
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
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
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

    function login()
    {
        return view('front.account.login');
    }

    function authenticate(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:5',
            ]
        );

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('profile');
            } else {
                return redirect()->route('login')->with('error', 'Email/Password is incorrect');
            }
        } else {
            return redirect()->route('login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    function profile()
    {
        $id = Auth::user()->id;
        $user = User::find($id);

        return view('front.account.profile', [
            'user' =>  $user,
        ]);
    }

    function updateProfile(Request $request)
    {
        $id = Auth::user()->id;
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:5|max:20',
                'email' => 'required|email|unique:users,email,' . $id . ',id',
            ]
        );


        if ($validator->passes()) {
            // การใช้ update แบบ save นี้จะไม่ต้องเพิ่ม fillable ใน model
            // $user = User::find($id);
            // $user->name = $request->name;
            // $user->email = $request->email;
            // $user->designation = $request->designation;
            // $user->mobile = $request->mobile;
            // $user->save();


            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'designation' => $request->designation,
                'mobile' => $request->mobile
            ];

            // การใช้ update แบบ find()จะต้องเพิ่ม fillable ใน model ด้วย
            // User::find($id)->update($data);

            User::where('id', $id)->update($data);

            session()->flash('success', 'Profile updated successfully.');

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

    function updateProfilePic(Request $request)
    {
        $id = Auth::user()->id;

        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|image',
            ]
        );

        if ($validator->passes()) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id . "-" . time() . "." . $ext;
            $image->move(public_path("/profile_pic/"), $imageName);

            // create small thumnail
            $sourcePath = public_path("/profile_pic/" . $imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);
            $image->cover(150, 150);
            $image->toPng()->save(public_path("/profile_pic/thumb/") . $imageName);

            //delete old pic
            File::delete(public_path("/profile_pic/thumb/")  . Auth::user()->image);
            File::delete(public_path("/profile_pic/") . Auth::user()->image);

            $data = [
                'image' => $imageName,
            ];

            // การใช้ update แบบ find()จะต้องเพิ่ม fillable ใน model ด้วย
            // User::find($id)->update($data);

            User::where('id', $id)->update($data);

            session()->flash('success', 'Profile picture updated successfully.');

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
}
