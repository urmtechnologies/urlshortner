<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ClientInvitationMail;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InviteController extends Controller
{
    public function index(Request $request)
    {
        $invitations = Invitation::where('client_id', $request->user()->client_id)->latest()->paginate(10);

        return view('admin.invite', compact('invitations'));
    }

    public function store(Request $request)
    {
        $request->merge(['email' => strtolower(trim((string) $request->email))]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'in:admin,member'],
        ]);
        if (User::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages(['email' => 'This email already belongs to a user.']);
        }
        $existing = Invitation::where('email', $data['email'])->first();
        if ($existing && $existing->expires_at->isFuture() && ! $existing->accepted_at) {
            throw ValidationException::withMessages(['email' => 'An active invitation has already been sent.']);
        }
        $token = Str::random(64);
        DB::transaction(function () use ($existing, $request, $data, $token) {
            $invite = $existing ?? new Invitation;
            $invite->fill([
                'client_id' => $request->user()->client_id, 'client_name' => trim($data['name']),
                'email' => $data['email'], 'role' => $data['role'], 'token_hash' => hash('sha256', $token),
                'invited_by_id' => $request->user()->id, 'expires_at' => now()->addDays(7), 'accepted_at' => null,
            ])->save();
            Mail::to($invite->email)->send(new ClientInvitationMail($request->user()->client->name, route('invitation.accept', $token), $data['role']));
        });

        return redirect()->route('admin.invite.index')->with('success', 'Invitation email sent.');
    }
}
