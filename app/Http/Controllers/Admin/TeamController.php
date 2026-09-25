<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('client_id', $request->user()->client_id)->whereIn('role', ['admin', 'member'])
            ->withCount('shortUrls')->withSum('shortUrls', 'hits_count')->latest()->paginate(10);
        $invitations = Invitation::where('client_id', $request->user()->client_id)
            ->whereNull('accepted_at')->latest()->get();

        return view('admin.members', compact('users', 'invitations'));
    }
}
