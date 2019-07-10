<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $user->children->load('borrows');

        return view('home', compact('user'));
    }
}
