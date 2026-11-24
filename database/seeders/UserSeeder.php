<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates starter accounts. Change these passwords immediately outside local development.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEED_USER_PASSWORD', 'password');

        $accounts = [
            ['name' => 'Site Administrator', 'email' => 'admin@isocjm.org', 'role' => UserRole::Admin, 'status' => UserStatus::Approved],
            ['name' => 'Content Editor', 'email' => 'editor@isocjm.org', 'role' => UserRole::Editor, 'status' => UserStatus::Approved],
            ['name' => 'Demo Member', 'email' => 'member@isocjm.org', 'role' => UserRole::Member, 'status' => UserStatus::Approved],
            ['name' => 'Pending Applicant', 'email' => 'pending@isocjm.org', 'role' => UserRole::Member, 'status' => UserStatus::Pending],
        ];

        foreach ($accounts as $account) {
            $user = User::firstOrNew(['email' => $account['email']]);
            $user->forceFill([
                'name' => $account['name'],
                'password' => $password,
                'role' => $account['role'],
                'status' => $account['status'],
                'approved_at' => $account['status'] === UserStatus::Approved ? now() : null,
                'email_verified_at' => now(),
                'stakeholder_group' => 'Technical Community',
                'parish' => 'Kingston',
            ])->save();
        }

        if (app()->environment('local')) {
            User::factory()->count(12)->create();
            User::factory()->count(3)->pending()->create();
        }
    }
}
