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
        $user->children->each(function ($child) {
            $child->load('borrows');
        });

        return view('home', compact('user'));
    }

    /**
     * Borrow things history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function borrow_history(Request $request)
    {
        $user = $request->user();
        $histories = $user
            ->histories()
            ->with(['item', 'parent'])
            ->latest()
            ->paginate(20);
        $is_my = true;

        return view('users.borrow_history', compact('user', 'histories', 'is_my'));
    }

    /**
     * Proxy borrow children things history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function proxy_borrow_history(Request $request)
    {
        $user = $request->user();
        $proxy_histories = $user
            ->proxy_histories()
            ->with(['item', 'user'])
            ->latest()
            ->paginate(20);
        $is_my = true;

        return view('users.proxy_history', compact('user', 'proxy_histories', 'is_my'));
    }
}
