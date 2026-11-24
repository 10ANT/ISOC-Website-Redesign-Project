<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserRequest extends AdminRequest
{
    public function rules(): array
    {
        $user = $this->route('user');
        $editingSelf = $user->is($this->user());

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            // Admins cannot demote or suspend themselves and get locked out.
            'role' => ['required', Rule::enum(UserRole::class), $editingSelf ? Rule::in([UserRole::Admin->value]) : 'sometimes'],
            'status' => ['required', Rule::enum(UserStatus::class), $editingSelf ? Rule::in([UserStatus::Approved->value]) : 'sometimes'],
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
            'role.in' => 'You cannot remove your own administrator role.',
            'status.in' => 'You cannot suspend or un-approve your own account.',
        ];
    }
}
