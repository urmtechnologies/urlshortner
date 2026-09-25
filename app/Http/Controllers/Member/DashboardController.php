<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $urls = ShortUrl::where('user_id', $request->user()->id)->latest()->limit(10)->get();

        return view('member.dashboard', compact('urls'));
    }
}
