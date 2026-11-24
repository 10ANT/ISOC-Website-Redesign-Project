<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::putMany([
            'hero_tagline' => 'Internet Society · Jamaica Chapter',
            'hero_title' => 'The Internet is for everyone.',
            'hero_text' => 'ISOC Jamaica works to keep the Internet open, secure and accessible for every Jamaican, through policy, community networks, skills and partnerships.',
            'intro_title' => 'A local voice for a global, open Internet',
            'intro_text' => "ISOC Jamaica is the Jamaican chapter of the Internet Society. We bring together end users, students, academia, civil society, the technical community, government and the private sector to shape how the Internet grows in Jamaica.\n\nWe run webinars, workshops and training, contribute to Internet governance discussions, and support projects that connect communities and keep people safe online.",
            'membership_intro' => 'Membership is free and open to anyone who shares our belief that the Internet is for everyone. Members take part in community engagement, policy development and Internet governance, and get access to members-only updates and resources.',
            'isoc_global_url' => 'https://www.internetsociety.org/become-a-member/',
            'contact_email' => 'info@isocjm.org',
            'contact_phone' => '(876) 838-2408',
            'contact_address' => 'Kingston, Jamaica',
            'office_hours' => 'Monday – Friday, 9:00 am – 5:00 pm',
            'social_facebook' => 'https://www.facebook.com/',
            'social_x' => 'https://x.com/',
            'social_linkedin' => 'https://www.linkedin.com/',
            'social_instagram' => null,
            'social_youtube' => 'https://www.youtube.com/',
        ]);
    }
}
