<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    function index()
    {
        $jobApplications = JobApplication::orderBy('created_at', 'DESC')->with(['job', 'job.jobType', 'job.applications', 'employer', 'user'])->paginate(10);

        return view("admin.job-applications.list", [
            'jobApplications' => $jobApplications
        ]);
    }

    function destroy(Request $request)
    {
        $user = JobApplication::where('id', $request->id)->first();

        if ($user == null) {
            session()->flash('error', 'Either job application deleted or not found.');
            return response()->json([
                'status' => false,
            ]);
        }

        JobApplication::where('id', $request->id)->delete();

        session()->flash('success', 'Job application deleted successfully.');

        return response()->json([
            'status' => true,
        ]);
    }
}
