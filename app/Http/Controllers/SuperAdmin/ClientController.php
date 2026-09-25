<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with(['users' => fn ($q) => $q->where('role', 'admin')])
            ->withCount(['users', 'shortUrls'])
            ->withSum('shortUrls', 'hits_count')->latest()->paginate(10);

        return view('superadmin.clients', compact('clients'));
    }

    public function show(Client $client)
    {
        $users = $client->users()->withCount('shortUrls')->withSum('shortUrls', 'hits_count')->latest()->paginate(10);
        $urls = $client->shortUrls()->with('user')->latest()->limit(10)->get();

        return view('superadmin.client', compact('client', 'users', 'urls'));
    }
}
