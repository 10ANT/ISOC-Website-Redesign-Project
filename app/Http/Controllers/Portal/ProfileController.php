<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('portal.profile', [
            'user' => $request->user(),
            'stakeholderGroups' => User::STAKEHOLDER_GROUPS,
            'parishes' => User::PARISHES,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $user->update($request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'organisation' => ['nullable', 'string', 'max:150'],
            'job_title' => ['nullable', 'string', 'max:150'],
            'stakeholder_group' => ['nullable', Rule::in(User::STAKEHOLDER_GROUPS)],
            'parish' => ['nullable', Rule::in(User::PARISHES)],
            'bio' => ['nullable', 'string', 'max:2000'],
            'interests' => ['nullable', 'string', 'max:1000'],
        ]));

        return back()->with('success', 'Your profile has been updated.');
    }

    public function password(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update(['password' => $validated['password']]);

        return back()->with('success', 'Your password has been changed.');
    }
}
