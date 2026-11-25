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

/**
 * Accounts for the chapter leaders who manage the website. There is no public
 * sign-up, so administrators add each leader here.
 */
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
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'statuses' => UserStatus::cases(),
            'roles' => UserRole::cases(),
        ]);
    }

    public function create(): View
    {
        return $this->form((new User)->forceFill(['role' => UserRole::Editor, 'status' => UserStatus::Approved]));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $user = new User;
        $this->save($user, $request->validated());

        return redirect()->route('admin.users.index')->with('success', "{$user->name} can now sign in to the website admin.");
    }

    public function edit(User $user): View
    {
        return $this->form($user);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->save($user, $request->validated());

        return redirect()->route('admin.users.index')->with('success', "{$user->name}'s account has been updated.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'Account deleted.');
    }

    private function form(User $user): View
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => User::LEADER_ROLES,
            'statuses' => [UserStatus::Approved, UserStatus::Suspended],
            'stakeholderGroups' => User::STAKEHOLDER_GROUPS,
            'parishes' => User::PARISHES,
        ]);
    }

    private function save(User $user, array $data): void
    {
        $user->fill(collect($data)->except(['role', 'status', 'password'])->all());

        if (filled($data['password'] ?? null)) {
            $user->password = $data['password'];
        }

        $user->role = UserRole::from($data['role']);
        $status = UserStatus::from($data['status']);

        if ($status === UserStatus::Approved && $user->status !== UserStatus::Approved) {
            $user->approved_at = now();
        }

        $user->status = $status;
        $user->save();
    }
}
