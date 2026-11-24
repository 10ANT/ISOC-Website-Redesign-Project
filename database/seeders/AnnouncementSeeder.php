<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('email', 'admin@isocjm.org')->value('id');

        $announcements = [
            [
                'title' => 'Welcome to the new member portal',
                'body' => "Welcome! This portal is your home as an ISOC Jamaica member. Here you'll find members-only announcements, resources such as the Member Handbook, and upcoming events.\n\nPlease take a moment to **complete your profile** so we can connect you with the right working groups.",
                'is_pinned' => true, 'days_ago' => 1,
            ],
            [
                'title' => 'Call for volunteers: Policy Working Group',
                'body' => "We're forming a Policy Working Group to help prepare ISOC Jamaica's responses to public consultations on digital policy. No prior policy experience needed, just interest and a few hours a month.\n\nReply via the contact form, choosing \"Programmes & partnerships\".",
                'days_ago' => 6,
            ],
            [
                'title' => 'Annual General Meeting: save the date',
                'body' => "Our Annual General Meeting will be held later this year. Members in good standing will be able to vote on Chapter business. Full details and the agenda will be shared here.",
                'days_ago' => 14,
            ],
        ];

        foreach ($announcements as $data) {
            $daysAgo = $data['days_ago'];
            unset($data['days_ago']);

            Announcement::updateOrCreate(['title' => $data['title']], [
                ...$data,
                'user_id' => $adminId,
                'published_at' => now()->subDays($daysAgo),
            ]);
        }
    }
}
