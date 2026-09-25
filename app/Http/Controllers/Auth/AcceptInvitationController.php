<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AcceptInvitationController extends Controller
{
    public function show(string $token)
    {
        $invitation = Invitation::where('token_hash', hash('sha256', $token))->firstOrFail();
        abort_if($invitation->accepted_at || $invitation->expires_at->isPast(), 410, 'Invitation expired or already accepted.');

        return view('auth.accept-invitation', compact('invitation', 'token'));
    }

    public function store(Request $request, string $token)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:150'], 'password' => ['required', 'string', 'min:8', 'confirmed']]);
        $user = DB::transaction(function () use ($token, $data) {
            $invite = Invitation::where('token_hash', hash('sha256', $token))->lockForUpdate()->first();
            abort_if(! $invite || $invite->accepted_at || $invite->expires_at->isPast(), 410, 'Invitation expired or already accepted.');
            if (User::where('email', $invite->email)->exists()) {
                throw ValidationException::withMessages(['name' => 'An account already exists with this email.']);
            }
            if ($invite->client_id) {
                $client = Client::findOrFail($invite->client_id);
                abort_unless(in_array($invite->role, ['admin', 'member'], true), 403);
                abort_unless(User::where('id', $invite->invited_by_id)->where('client_id', $client->id)->where('role', 'admin')->exists(), 403);
            } else {
                abort_unless($invite->role === 'admin' && User::where('id', $invite->invited_by_id)->where('role', 'superadmin')->exists(), 403);
                $client = Client::create(['name' => $invite->client_name]);
            }
            $user = User::create(['name' => $data['name'], 'email' => $invite->email, 'password' => $data['password'],
                'role' => $invite->role, 'client_id' => $client->id, 'invited_by_id' => $invite->invited_by_id]);
            $invite->update(['accepted_at' => now()]);

            return $user;
        });
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($user->role === 'admin' ? 'admin.dashboard' : 'member.dashboard');
    }
}
