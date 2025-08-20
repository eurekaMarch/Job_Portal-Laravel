<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function index()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(10);

        return view("admin.users.list", [
            'users' =>  $users,
        ]);
    }

    function edit($id)
    {
        $user = User::findOrFail($id);
        return view("admin.users.edit", [
            'user' =>  $user,
        ]);
    }

    function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:5|max:20',
                'email' => 'required|email|unique:users,email,' . $id . ',id',
            ]
        );


        if ($validator->passes()) {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'designation' => $request->designation,
                'mobile' => $request->mobile
            ];

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

    function destroy(Request $request)
    {
        $user = User::where('id', $request->id)->first();

        if ($user == null) {
            session()->flash('error', 'User not found.');
            return response()->json([
                'status' => false,
            ]);
        }

        User::where('id', $request->id)->delete();

        session()->flash('success', 'User deleted successfully.');

        return response()->json([
            'status' => true,
        ]);
    }
}
