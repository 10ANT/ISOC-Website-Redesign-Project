<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends AdminRequest
{
    public function rules(): array
    {
        $user = $this->route('user');
        $editingSelf = $user?->is($this->user()) ?? false;
        $roles = collect(User::LEADER_ROLES)->map->value->all();

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            // Admins cannot demote or suspend themselves and get locked out.
            'role' => ['required', Rule::in($editingSelf ? [UserRole::Admin->value] : $roles)],
            'status' => ['required', Rule::in($editingSelf
                ? [UserStatus::Approved->value]
                : [UserStatus::Approved->value, UserStatus::Suspended->value])],
            // New accounts need a password; on existing accounts it is optional and resets the password.
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:40'],
            'organisation' => ['nullable', 'string', 'max:150'],
            'job_title' => ['nullable', 'string', 'max:150'],
            'stakeholder_group' => ['nullable', Rule::in(User::STAKEHOLDER_GROUPS)],
            'parish' => ['nullable', Rule::in(User::PARISHES)],
        ];
    }

    public function messages(): array
    {
        return [
            'role.in' => 'Choose Administrator or Editor. You cannot remove your own administrator role.',
            'status.in' => 'You cannot suspend your own account.',
        ];
    }
}
