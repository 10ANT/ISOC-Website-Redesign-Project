<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const STAKEHOLDER_GROUPS = [
        'Academia', 'Civil Society', 'End User', 'Private Sector',
        'Public Sector', 'Student', 'Technical Community',
    ];

    public const PARISHES = [
        'Kingston', 'St. Andrew', 'St. Thomas', 'Portland', 'St. Mary', 'St. Ann',
        'Trelawny', 'St. James', 'Hanover', 'Westmoreland', 'St. Elizabeth',
        'Manchester', 'Clarendon', 'St. Catherine', 'Overseas',
    ];

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'organisation', 'job_title',
        'stakeholder_group', 'parish', 'bio', 'interests',
    ];

    protected $hidden = ['password', 'remember_token'];

    /** New accounts are pending members until an administrator approves them. */
    protected $attributes = [
        'role' => 'member',
        'status' => 'pending',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isStaff(): bool
    {
        return in_array($this->role, [UserRole::Admin, UserRole::Editor], true);
    }

    public function isApproved(): bool
    {
        return $this->status === UserStatus::Approved;
    }

    public function isSuspended(): bool
    {
        return $this->status === UserStatus::Suspended;
    }

    /** Approved members (and staff) may see members-only content. */
    public function canViewMemberContent(): bool
    {
        return $this->isApproved() || $this->isStaff();
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }

    public function scopePending(Builder $query): void
    {
        $query->where('status', UserStatus::Pending);
    }
}
