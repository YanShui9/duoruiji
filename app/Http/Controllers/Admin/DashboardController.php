<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\Lecture;
use App\Models\Video;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'experts' => Expert::count(),
            'lectures' => Lecture::count(),
            'videos' => Video::count(),
            'live_now' => Lecture::where('status', 1)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
