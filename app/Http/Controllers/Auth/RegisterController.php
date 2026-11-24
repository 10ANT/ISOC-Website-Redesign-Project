<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Membership applications. New accounts start as "pending" members and
 * are approved by an administrator before seeing members-only content.
 */
class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'stakeholderGroups' => User::STAKEHOLDER_GROUPS,
            'parishes' => User::PARISHES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'organisation' => ['nullable', 'string', 'max:150'],
            'stakeholder_group' => ['required', Rule::in(User::STAKEHOLDER_GROUPS)],
            'parish' => ['nullable', Rule::in(User::PARISHES)],
            'interests' => ['nullable', 'string', 'max:1000'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'Please confirm you agree to the privacy policy.',
        ]);

        unset($data['terms']);

        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('portal.dashboard')
            ->with('success', 'Welcome to ISOC Jamaica! Your membership application has been received and is awaiting approval.');
    }
}
