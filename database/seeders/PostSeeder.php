<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Post;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $category = fn (string $name) => Category::where('type', CategoryType::News)->where('name', $name)->value('id');
        $programme = fn (string $title) => Programme::where('title', $title)->value('id');
        $authorId = User::where('email', 'editor@isocjm.org')->value('id');

        $posts = [
            [
                'title' => 'ISOC Jamaica launches its new website', 'image' => 'assets/images/photos/news-1.jpg',
                'excerpt' => 'Our new website brings events, news and resources together in one place.',
                'body' => "We're pleased to launch the new ISOC Jamaica website, a single home for everything the Chapter does.\n\n## What's new\n\n- **Events** are now listed automatically, with registration links and a full archive.\n- **News** like this post keeps you up to date between events.\n- **Resources** brings our reports, guides and presentations into one searchable library.\n- **Requests:** you can now ask us for a meeting, an event or a speaker, offer support, or suggest a project from the [Get Involved](/get-involved#requests) page.\n\nWant to join the Chapter? [See how to become a member](/membership) through the Internet Society.",
                'category_id' => $category('Chapter News'), 'tags' => ['website', 'members'], 'days_ago' => 2,
            ],
            [
                'title' => 'Five simple ways to protect yourself from phishing', 'image' => 'assets/images/photos/news-3.jpg',
                'excerpt' => 'Most cyber attacks start with a convincing message. These five habits will stop most of them.',
                'body' => "Phishing messages are designed to rush you into clicking. A few simple habits make a big difference.\n\n1. **Pause before you click.** Urgency is the scammer's best tool.\n2. **Check the sender.** Look closely at email addresses and phone numbers.\n3. **Hover before you tap.** Check where a link really goes.\n4. **Never share codes.** Your bank will never ask for a one-time password.\n5. **Turn on two-factor authentication** for email, banking and social media.\n\nJoin our upcoming Online Phishing Awareness Workshop to learn more.",
                'category_id' => $category('Cyber Safety'), 'programme_id' => $programme('Cyber Security'), 'tags' => ['phishing', 'online safety'], 'days_ago' => 9,
            ],
            [
                'title' => 'Why encryption matters for every Jamaican', 'image' => 'assets/images/photos/news-2.jpg',
                'excerpt' => 'From mobile banking to WhatsApp, encryption keeps everyday life safe. Here is why policy debates about it matter.',
                'body' => "Every time you check your bank balance, send a voice note or log in to a government service, encryption is protecting you.\n\nAround the world, some policymakers are proposing ways to give authorities access to encrypted data. While often well-intentioned, these proposals can create weaknesses that criminals can exploit too.\n\nISOC Jamaica supports strong encryption as a foundation of a trustworthy Internet, and we'll be discussing these issues at our upcoming webinar.",
                'category_id' => $category('Policy & Governance'), 'programme_id' => $programme('Internet Governance'), 'tags' => ['encryption', 'policy'], 'days_ago' => 18,
            ],
            [
                'title' => 'What are community networks, and could they work in Jamaica?', 'image' => 'assets/images/photos/tower.jpg',
                'excerpt' => 'Community-built networks are connecting rural areas around the world. We look at what it would take here.',
                'body' => "A community network is Internet infrastructure built and run by the people who use it. From Kenya to Argentina, they have connected places commercial providers overlooked.\n\nJamaica's hilly terrain and dispersed rural communities make connectivity expensive. Community networks could help, with the right partners, training and supportive regulation.\n\nWe'll be exploring this at our Community Networks Forum. We'd love to hear from communities interested in taking part.",
                'category_id' => $category('Community'), 'programme_id' => $programme('Community Networks'), 'tags' => ['connectivity', 'rural'], 'days_ago' => 33,
            ],
            [
                'title' => 'Chapter members join regional Internet governance discussions', 'image' => 'assets/images/photos/news-4.jpg',
                'excerpt' => 'ISOC Jamaica members took part in regional dialogues on connectivity, cyber security and digital inclusion.',
                'body' => "Members of the Chapter joined colleagues from across the Caribbean to discuss the region's digital priorities, including affordable access, cyber resilience and the role of youth in Internet governance.\n\nThese discussions help ensure Jamaican perspectives are reflected in regional and global policy.",
                'category_id' => $category('Policy & Governance'), 'programme_id' => $programme('Internet Governance'), 'tags' => ['IGF', 'Caribbean'], 'days_ago' => 51,
            ],
            [
                'title' => 'Secure your smart devices: an IoT checklist', 'image' => 'assets/images/photos/news-6.jpg',
                'excerpt' => 'Smart TVs, cameras and routers are easy targets. Use this checklist to lock them down.',
                'body' => "Connected devices often ship with weak default settings. Before you connect a new device:\n\n- Change the default password\n- Install updates and turn on automatic updates\n- Turn off features you don't use\n- Put smart devices on a separate guest network if your router allows it\n\nSmall steps like these block most automated attacks.",
                'category_id' => $category('Cyber Safety'), 'programme_id' => $programme('Internet of Things'), 'tags' => ['IoT', 'online safety'], 'days_ago' => 70,
            ],
        ];

        foreach ($posts as $data) {
            $daysAgo = $data['days_ago'];
            unset($data['days_ago']);

            Post::updateOrCreate(['title' => $data['title']], [
                ...$data,
                'user_id' => $authorId,
                'published_at' => now()->subDays($daysAgo)->setTime(10, 0),
            ]);
        }
    }
}
