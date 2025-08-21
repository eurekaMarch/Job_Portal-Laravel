<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    function index()
    {

        $jobs = Job::orderBy('created_at', 'DESC')->with('user', 'applications')->paginate(10);

        return view("admin.jobs.list", [
            'jobs' => $jobs
        ]);
    }

    function edit($id)
    {
        $job = Job::findOrFail($id);

        $categories = Category::orderBy('name', 'ASC')->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->get();

        return view("admin.jobs.edit", [
            'job' =>  $job,
            'categories' =>  $categories,
            'jobTypes' =>  $jobTypes,
        ]);
    }

    function update(Request $request, $jobId)
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
                'status' => $request->status,
                'isFeatured' => (!empty($request->isFeatured)) ? $request->isFeatured : 0,
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

    function destroy(Request $request)
    {
        $user = Job::where('id', $request->id)->first();

        if ($user == null) {
            session()->flash('error', 'Either job deleted or not found.');
            return response()->json([
                'status' => false,
            ]);
        }

        Job::where('id', $request->id)->delete();

        session()->flash('success', 'Job deleted successfully.');

        return response()->json([
            'status' => true,
        ]);
    }
}
