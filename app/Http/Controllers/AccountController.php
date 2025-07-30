<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
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
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

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

            // การใช้ update แบบ where นี้จะไม่ต้องเพิ่ม fillable ใน model
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

            $image->cover(150, 150)->save(public_path("/profile_pic/thumb/") . $imageName);


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

    function createJob()
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', true)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', true)->get();

        return view('front.account.job.create', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
        ]);
    }

    function saveJob(Request $request)
    {
        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required|min:3|max:75',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules
        );

        if ($validator->passes()) {
            $job = new Job();

            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;
            $job->save();


            session()->flash('success', 'Job added successfully.');

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

    function myJobs()
    {
        $id = Auth::user()->id;
        $jobs = Job::where('user_id', $id)->with(['jobType'])->orderBy('created_at', 'DESC')->paginate(10);

        return view('front.account.job.my-jobs', [
            'jobs' => $jobs,
        ]);
    }

    function editJob($jobId)
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', true)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', true)->get();

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $jobId,
        ])->first();

        if ($job == null) {
            abort(404);
        }

        return view('front.account.job.edit', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job,
        ]);
    }

    function updateJob(Request $request, $jobId)
    {
        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required|min:3|max:75',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules
        );

        if ($validator->passes()) {
            $data = [
                'title' => $request->title,
                'category_id' => $request->category,
                'job_type_id' => $request->jobType,
                'user_id' => Auth::user()->id,
                'vacancy' => $request->vacancy,
                'salary' => $request->salary,
                'location' => $request->location,
                'description' => $request->description,
                'benefits' => $request->benefits,
                'responsibility' => $request->responsibility,
                'qualifications' => $request->qualifications,
                'keywords' => $request->keywords,
                'experience' => $request->experience,
                'company_name' => $request->company_name,
                'company_location' => $request->company_location,
                'company_website' => $request->company_website,
            ];

            Job::where('id', $jobId)->update($data);


            session()->flash('success', 'Job updated successfully.');

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

    function deleteJob(Request $request)
    {
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobID,
        ])->first();

        if ($job == null) {
            session()->flash('error', 'Either job deleted or not found.');
            return response()->json([
                'status' => true,
            ]);
        }

        Job::where('id', $request->jobID)->delete();

        session()->flash('success', 'Job deleted successfully.');

        return response()->json([
            'status' => true,
        ]);
    }
}
