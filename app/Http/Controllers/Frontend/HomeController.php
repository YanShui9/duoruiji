<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // TODO: Task 10 实现
        return view('welcome');
    }
}
