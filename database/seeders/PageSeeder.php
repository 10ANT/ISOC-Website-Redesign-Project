<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'who-we-are' => [
                'title' => 'Who We Are',
                'summary' => 'ISOC Jamaica is the Jamaican chapter of the Internet Society, a global non-profit working for an open, globally connected, secure and trustworthy Internet.',
                'image' => 'assets/images/photos/about-team.jpg',
                'body' => "ISOC Jamaica brings together people from every part of Jamaican life who care about the future of the Internet: end users, students, educators, researchers, engineers, civil society, government and business.\n\nOur members come from diverse professional and educational backgrounds, with expertise in many disciplines. What unites us is a simple belief: **the Internet is for everyone.**\n\n## What we focus on\n\n- **Internet Governance:** making sure Jamaican perspectives are heard where Internet policy is made\n- **Community Networks:** helping underserved communities get connected on their own terms\n- **Internet of Things:** promoting secure and responsible connected devices\n- **Cyber Security:** building awareness and practices that keep people safe online\n\n## Part of a global community\n\nAs a chapter of the Internet Society, we are connected to more than 100 chapters and thousands of members worldwide, and we bring that global knowledge home to Jamaica.",
            ],
            'mission' => [
                'title' => 'Our Mission',
                'summary' => 'To further the Internet Society\'s work in Jamaica for the benefit of current users and those still waiting to be connected.',
                'body' => "ISOC Jamaica is established to further the Internet Society's work in Jamaica: for the benefit of users, and for the benefit of those who are about to come online or are still waiting for an affordable, reliable connection.\n\n## Our commitments\n\n1. **Access:** Promote affordable, meaningful connectivity for every Jamaican, in every parish.\n2. **Trust:** Encourage a secure, private and resilient Internet.\n3. **Participation:** Support an open, multistakeholder approach to Internet governance.\n4. **Skills:** Build the knowledge Jamaicans need to use, build and shape the Internet.\n\n> The Internet is for everyone. Our job is to help make that true in Jamaica.",
            ],
            'history' => [
                'title' => 'Our History',
                'summary' => 'How a group of Jamaican Internet advocates came together to form a local chapter of the Internet Society.',
                'body' => "ISOC Jamaica was founded by a group of Jamaicans from academia, the technical community, civil society, the public and private sectors, students and end users, who wanted a local home for Internet Society work.\n\nSince then, the Chapter has hosted webinars, workshops and training sessions on topics ranging from encryption policy to phishing awareness, and has connected Jamaicans to Internet Society fellowships and programmes.\n\n## Milestones\n\n*Key dates and milestones are to be confirmed with the ISOC Jamaica team and will be added here.*\n\nYou can meet the people who started the Chapter on our [leadership page](/about/leadership).",
            ],
            'our-plans' => [
                'title' => 'Our Plans',
                'summary' => 'Projects and priorities that will engage our communities and build awareness across Jamaica.',
                'body' => "In keeping with the Internet Society's goal of connecting the world, ISOC Jamaica will be undertaking several projects to engage our communities and create awareness of the issues that matter most.\n\n## Current priorities\n\n- **Grow membership and participation** across all parishes and stakeholder groups\n- **Expand community connectivity** by identifying and supporting community network pilots\n- **Strengthen online safety** through regular cyber security awareness workshops\n- **Deepen policy engagement** with government, regulators and regional partners\n- **Invest in young people** through training, fellowships and competitions\n\n*Detailed plans and timelines are to be confirmed with the ISOC Jamaica board.*",
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'summary' => 'How ISOC Jamaica collects, uses and protects personal information shared through this website.',
                'body' => "ISOC Jamaica respects your privacy. This policy explains what information we collect through this website and how we use it.\n\n## Information we collect\n\n- **Membership applications:** your name, email address, and any optional details you provide such as phone number, organisation and interests.\n- **Contact and volunteer forms:** the details you submit so we can respond to you.\n- **Technical information:** standard server logs and a session cookie needed to keep you signed in.\n\n## How we use it\n\nWe use your information only to run the Chapter: to manage membership, respond to enquiries, and share Chapter news and events. We do not sell your information.\n\n## Your choices\n\nMembers can update their profile at any time from the member portal. To have your account or data removed, contact us.\n\n*This policy is a starting draft and should be reviewed and approved by ISOC Jamaica before launch.*",
            ],
        ];

        foreach ($pages as $slug => $data) {
            Page::updateOrCreate(['slug' => $slug], [...$data, 'is_published' => true]);
        }
    }
}
