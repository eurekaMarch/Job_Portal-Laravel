<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    function index()
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', true)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', true)->get();
        $jobs = Job::where('status', true)->with(['jobType'])->orderBy('created_at', 'DESC')->paginate(9);


        return view('front.jobs', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
        ]);
    }
}
