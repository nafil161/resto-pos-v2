<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function page1()
    {
        return view('pages.page1');
    }

    public function page2()
    {
        return view('pages.page2');
    }
}
