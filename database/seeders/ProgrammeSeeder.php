<?php

namespace Database\Seeders;

use App\Enums\ProgrammeStatus;
use App\Models\Programme;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    public function run(): void
    {
        $programmes = [
            [
                'title' => 'Internet Governance', 'image' => 'assets/images/photos/governance.jpg',
                'icon' => 'fa-landmark',
                'summary' => 'Bringing Jamaican voices into the decisions that shape the Internet, locally, regionally and globally.',
                'body' => "Internet governance covers the policies, standards and practices that determine how the Internet works and who it works for. ISOC Jamaica helps Jamaicans take part in these conversations.\n\n## What we do\n\n- Host public dialogues on national digital policy, data protection and connectivity\n- Support participation in regional and global forums such as the Caribbean Internet Governance Forum and the global IGF\n- Respond to public consultations with evidence-based recommendations\n- Build understanding of the multistakeholder model among students and young professionals\n\n## Get involved\n\nMembers can join our policy working group to help draft submissions and plan dialogues.",
            ],
            [
                'title' => 'Community Networks', 'image' => 'assets/images/photos/community-networks.jpg',
                'icon' => 'fa-tower-broadcast',
                'summary' => 'Helping underserved communities build and run their own Internet connectivity.',
                'body' => "Community networks are connectivity projects built, owned and operated by the communities they serve. They are a proven way to reach places that commercial networks overlook.\n\n## Our approach\n\n- Identify rural and underserved communities with the will to build their own network\n- Partner with local organisations, schools and churches as anchor sites\n- Provide technical training so communities can maintain their networks independently\n- Share lessons learned with policymakers to make community networks easier to start\n\n*Specific project sites are to be confirmed with the ISOC Jamaica team.*",
            ],
            [
                'title' => 'Internet of Things', 'image' => 'assets/images/photos/iot.jpg',
                'icon' => 'fa-microchip',
                'summary' => 'Promoting secure, privacy-respecting connected devices for homes, businesses and government.',
                'body' => "From smart meters to agricultural sensors, connected devices are becoming part of daily life in Jamaica. They bring real benefits, but only if they are built and used securely.\n\n## Focus areas\n\n- Raising awareness of IoT security basics among consumers and small businesses\n- Promoting the IoT Security Policy Platform principles\n- Exploring IoT for agriculture, utilities and disaster preparedness\n- Encouraging students to build and test IoT solutions responsibly",
            ],
            [
                'title' => 'Cyber Security', 'image' => 'assets/images/photos/cyber-security.jpg',
                'icon' => 'fa-shield-halved',
                'summary' => 'Building a safer Internet through awareness, training and stronger security practices.',
                'body' => "A trusted Internet is one people feel safe using. ISOC Jamaica works with partners to raise cyber security awareness and strengthen the practices that protect Jamaican users.\n\n## What we do\n\n- Run phishing and online safety awareness workshops\n- Promote encryption and the Mutually Agreed Norms for Routing Security (MANRS)\n- Share practical guides for individuals, schools and small businesses\n- Contribute to national cyber security discussions",
            ],
        ];

        foreach ($programmes as $i => $data) {
            Programme::updateOrCreate(['title' => $data['title']], [
                ...$data,
                'status' => ProgrammeStatus::Active,
                'is_featured' => true,
                'sort_order' => $i + 1,
            ]);
        }

        Programme::updateOrCreate(['title' => 'Youth & Skills Development'], [
            'icon' => 'fa-graduation-cap', 'image' => 'assets/images/photos/youth.jpg',
            'summary' => 'Fellowships, training and competitions that grow the next generation of Internet leaders.',
            'body' => "We help students and young professionals build the skills to take part in the Internet's future.\n\n- Webinars and workshops on Internet technology and policy\n- Support for Internet Society fellowship and youth ambassador applications\n- Competitions that reward creative, community-focused ideas",
            'status' => ProgrammeStatus::Active,
            'is_featured' => false,
            'sort_order' => 5,
        ]);
    }
}
