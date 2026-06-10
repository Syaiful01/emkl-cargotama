<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->update(['last_login_at' => now()]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'user' => $user->load('role'),
                    'message' => 'Login successful'
                ]);
            }

            return redirect()->intended(route('dashboard'));
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        return redirect()->route('login');
    }

    public function me()
    {
        return response()->json(Auth::user()->load('role'));
    }
}
