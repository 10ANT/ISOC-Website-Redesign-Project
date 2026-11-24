<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $status = UserStatus::tryFrom((string) $request->query('status'));
        $role = UserRole::tryFrom((string) $request->query('role'));

        $users = User::query()
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('organisation', 'like', "%{$term}%")))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($role, fn ($q) => $q->where('role', $role))
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'statuses' => UserStatus::cases(),
            'roles' => UserRole::cases(),
            'pendingCount' => User::pending()->count(),
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => UserRole::cases(),
            'statuses' => UserStatus::cases(),
            'stakeholderGroups' => User::STAKEHOLDER_GROUPS,
            'parishes' => User::PARISHES,
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->fill(collect($data)->except(['role', 'status'])->all());
        $user->role = UserRole::from($data['role']);
        $this->setStatus($user, UserStatus::from($data['status']));
        $user->save();

        return redirect()->route('admin.users.index')->with('success', "{$user->name}'s account has been updated.");
    }

    public function approve(User $user): RedirectResponse
    {
        $this->setStatus($user, UserStatus::Approved);
        $user->save();

        return back()->with('success', "{$user->name} has been approved as a member.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'Account deleted.');
    }

    private function setStatus(User $user, UserStatus $status): void
    {
        if ($status === UserStatus::Approved && $user->status !== UserStatus::Approved) {
            $user->approved_at = now();
        }

        $user->status = $status;
    }
}
