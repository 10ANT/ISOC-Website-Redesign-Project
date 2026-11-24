<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Event;
use App\Models\Programme;
use Illuminate\Database\Seeder;

/**
 * Sample events with dates relative to "now" so the site always has upcoming items.
 */
class EventSeeder extends Seeder
{
    public function run(): void
    {
        $category = fn (string $name) => Category::where('type', CategoryType::Event)->where('name', $name)->value('id');
        $programme = fn (string $title) => Programme::where('title', $title)->value('id');
        $day = fn (int $offset, int $hour) => now()->startOfDay()->addDays($offset)->setTime($hour, 0);

        $events = [
            [
                'title' => 'Online Phishing Awareness Workshop', 'image' => 'assets/images/photos/cyber-security.jpg',
                'summary' => 'Learn to recognise and report phishing emails, texts and social media scams in this free, practical online workshop.',
                'description' => "Phishing is still the most common way criminals get into personal and business accounts. In this hands-on session we will walk through real examples targeting Jamaicans and show you how to spot the warning signs.\n\n## You will learn\n\n- How phishing emails, SMS (\"smishing\") and WhatsApp scams work\n- The red flags to look for before you click\n- How to check a link safely\n- What to do and who to tell if you think you've been caught\n\nOpen to everyone. No technical background needed.",
                'starts_at' => $day(12, 18), 'ends_at' => $day(12, 20),
                'online_url' => 'https://zoom.us/', 'registration_url' => 'https://forms.gle/',
                'category_id' => $category('Workshop'), 'programme_id' => $programme('Cyber Security'), 'is_featured' => true,
            ],
            [
                'title' => 'Global Policy Threats to Encryption', 'image' => 'assets/images/photos/event-talk.jpg',
                'summary' => 'A webinar on why encryption matters for everyday Jamaicans and how policy proposals around the world could weaken it.',
                'description' => "Encryption protects our banking, health records and private conversations. Yet governments around the world are considering proposals that could weaken it.\n\nJoin our panel of local and regional experts for a discussion on what these proposals mean for Jamaica and the Caribbean, followed by audience Q&A.",
                'starts_at' => $day(26, 14), 'ends_at' => $day(26, 16),
                'online_url' => 'https://zoom.us/', 'registration_url' => 'https://forms.gle/',
                'category_id' => $category('Webinar'), 'programme_id' => $programme('Internet Governance'),
            ],
            [
                'title' => 'Community Networks Forum: Connecting Rural Jamaica', 'image' => 'assets/images/photos/event-audience.jpg',
                'summary' => 'An in-person forum bringing together community leaders, ISPs and regulators to discuss community-led connectivity.',
                'description' => "How can rural communities build their own Internet access? This forum brings together community leaders, service providers, regulators and technologists to share experiences and plan next steps.\n\nLunch will be provided. Space is limited, so please register early.",
                'starts_at' => $day(41, 9), 'ends_at' => $day(41, 15),
                'venue' => 'University of the West Indies, Mona Campus, Kingston', 'registration_url' => 'https://forms.gle/',
                'category_id' => $category('Forum'), 'programme_id' => $programme('Community Networks'),
            ],
            [
                'title' => 'IoT Security Bootcamp for Students', 'image' => 'assets/images/photos/iot.jpg',
                'summary' => 'A two-day practical training for university students on building and securing Internet of Things devices.',
                'description' => "Get hands-on with microcontrollers, sensors and secure-by-design principles. Participants will work in teams to build a small IoT prototype and test its security.\n\nOpen to full-time tertiary students. Laptops required.",
                'starts_at' => $day(58, 9), 'ends_at' => $day(59, 16),
                'venue' => 'University of Technology, Jamaica, Papine', 'registration_url' => 'https://forms.gle/',
                'category_id' => $category('Training'), 'programme_id' => $programme('Internet of Things'),
            ],
            [
                'title' => 'Youth Internet Governance Essay Competition', 'image' => 'assets/images/photos/youth.jpg',
                'summary' => 'Students across Jamaica are invited to share their vision for an Internet that works for everyone.',
                'description' => "Write 1,000 to 1,500 words on the theme \"An Internet for every Jamaican\". Winners will receive prizes and the chance to present at our next public forum.\n\nOpen to secondary and tertiary students. Full rules on the registration page.",
                'starts_at' => $day(75, 23), 'ends_at' => null,
                'online_url' => 'https://forms.gle/', 'registration_url' => 'https://forms.gle/',
                'category_id' => $category('Competition'), 'programme_id' => $programme('Youth & Skills Development'),
            ],
            [
                'title' => 'Introduction to Internet Governance', 'image' => 'assets/images/photos/event-roadshow.jpg',
                'summary' => 'A recorded beginner session explaining who runs the Internet and how you can have a say.',
                'description' => "This introductory webinar explained the multistakeholder model, the key organisations involved in running the Internet, and how Jamaicans can take part.",
                'starts_at' => $day(-30, 18), 'ends_at' => $day(-30, 19),
                'online_url' => 'https://www.youtube.com/', 'category_id' => $category('Webinar'), 'programme_id' => $programme('Internet Governance'),
            ],
            [
                'title' => 'Safer Internet Day Schools Outreach', 'image' => 'assets/images/photos/event-group.jpg',
                'summary' => 'Chapter volunteers visited schools in Kingston and St. Andrew to talk about staying safe online.',
                'description' => "Volunteers delivered short interactive sessions to students on passwords, privacy settings and dealing with cyberbullying.",
                'starts_at' => $day(-90, 9), 'ends_at' => $day(-90, 14),
                'venue' => 'Kingston & St. Andrew', 'category_id' => $category('Workshop'), 'programme_id' => $programme('Cyber Security'),
            ],
        ];

        foreach ($events as $data) {
            Event::updateOrCreate(['title' => $data['title']], [...$data, 'is_published' => true]);
        }
    }
}
