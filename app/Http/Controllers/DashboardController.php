<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $allApps     = config('apps', []);
        $myAppSlugs  = auth()->user()->appSubscriptions()->pluck('app_slug')->toArray();
        $myApps      = array_intersect_key($allApps, array_flip($myAppSlugs));

        return view('dashboard', compact('allApps', 'myApps', 'myAppSlugs'));
    }
}
