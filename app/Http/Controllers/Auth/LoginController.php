<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        if ($request->user()->isSuspended()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Your account has been suspended. Please contact ISOC Jamaica.',
            ]);
        }

        // Sign-in is only for leaders who manage the website; membership lives with the Internet Society.
        if ($request->user()->cannot('access-admin')) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'This sign-in is only for chapter leaders who manage the website. To become a member, join through the Internet Society.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
