<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email = strtolower(trim((string) $request->input('email')));

        $validator = Validator::make(
            [
                'email' => $email,
                'password' => $request->input('password'),
            ],
            [
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please check the form.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = [
            'email' => $email,
            'password' => $request->input('password'),
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['Email or password is incorrect.'],
            ]);
        }

        $request->session()->regenerate();

        $route = match (Auth::user()->role) {
            'superadmin' => 'superadmin.dashboard',
            'admin' => 'admin.dashboard',
            'member' => 'member.dashboard',
            default => null,
        };

        if ($route === null) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Your account role is invalid.',
            ], 403);
        }

        return response()->json([
            'message' => 'Login successful.',
            'redirect' => route($route),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
