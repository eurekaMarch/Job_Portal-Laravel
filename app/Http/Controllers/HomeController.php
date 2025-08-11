<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function index()
    {
        $categories = Category::where('status', true)->orderBy('name', 'ASC')->take(8)->get();
        $featuredJobs = Job::where('status', true)->with(['jobType'])->orderBy('created_at', 'DESC')->where('isFeatured', true)->take(6)->get();
        $lastestJobs = Job::where('status', true)->with(['jobType'])->orderBy('created_at', 'DESC')->take(6)->get();

        $newCategories = Category::where('status', true)->orderBy('name', 'ASC')->get();

        return view("front.home", [
            'categories' => $categories,
            'featuredJobs' => $featuredJobs,
            'lastestJobs' => $lastestJobs,
            'newCategories' => $newCategories,
        ]);
    }
}
