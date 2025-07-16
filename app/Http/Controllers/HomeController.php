<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    function index () {
        return view("front.home");
    }

    function contact () {
        return view("front.contact");
    }
}
