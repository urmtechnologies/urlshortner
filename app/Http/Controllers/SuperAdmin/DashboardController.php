<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ShortUrl;

class DashboardController extends Controller
{
    public function index()
    {
        $clients = Client::withCount(['users', 'shortUrls'])->withSum('shortUrls', 'hits_count')->latest()->limit(5)->get();
        $urls = ShortUrl::with(['user', 'client'])->latest()->limit(5)->get();

        return view('superadmin.dashboard', compact('clients', 'urls'));
    }
}
