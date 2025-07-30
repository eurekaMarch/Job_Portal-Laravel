<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsController extends Controller
{
    function index(Request $request)
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', true)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', true)->get();
        $jobs = Job::where('status', true);

        if (!empty($request->keyword)) {
            $jobs = $jobs->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->keyword . '%');
                $query->orWhere('keywords', 'like', '%' . $request->keyword . '%');
            });
        }

        if (!empty($request->location)) {

            $jobs = $jobs->where('location', 'like', '%' . $request->location . '%');
        }

        if (!empty($request->category)) {

            $jobs = $jobs->where('category_id', $request->category);
        }

        $jobTypeArray = [];

        if (!empty($request->jobType)) {
            $jobTypeArray = explode(',', $request->jobType);

            $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
        }

        if (!empty($request->experience)) {

            $jobs = $jobs->where('experience', $request->experience);
        }

        $jobs = $jobs->with(['jobType']);

        if ($request->sort === '1') {
            $jobs = $jobs->orderBy('created_at', 'ASC')->paginate(9);
        } else {
            $jobs = $jobs->orderBy('created_at', 'DESC')->paginate(9);
        }

        return view('front.jobs', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray,
        ]);
    }

    function jobDetail($jobId)
    {
        $job = Job::where([
            'id' => $jobId,
            'status' => true,
        ])->with(['jobType', 'category'])->first();

        if ($job == null) {
            abort(404);
        }

        return view('front.jobDetail', [
            'job' => $job,
        ]);
    }

    function applyJob(Request $request)
    {
        $job = Job::where('id', $request->jobID)->first();

        if ($job == null) {
            session()->flash('error', 'Job does not exist.');
            return response()->json([
                'status' => true,
                'message'  => 'Job does not exist.',
            ]);
        }

        $employer_id = $job->user_id;

        if ($employer_id == Auth::user()->id) {
            session()->flash('error', 'You can not apply on your own job.');
            return response()->json([
                'status' => false,
                'message'  => 'You can not apply on your own job.',
            ]);
        }

        $jobApplictionCount = JobApplication::where([
            'user_id' => Auth::user()->id,
            'job_id' => $request->jobID,
        ])->count();

        if ($jobApplictionCount > 0) {
            session()->flash('error', 'You already applied on this job.');
            return response()->json([
                'status' => false,
                'message'  => 'You already applied on this job.',
            ]);
        }

        $application = new JobApplication();
        $application->job_id = $request->jobID;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();

        session()->flash('success', 'You have applied successfully.');

        return response()->json([
            'status' => true,
            'message' => 'You have applied successfully.',
        ]);
    }
}
