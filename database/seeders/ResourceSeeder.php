<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Enums\ResourceType;
use App\Models\Category;
use App\Models\Programme;
use App\Models\Resource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $topic = fn (string $name) => Category::where('type', CategoryType::Resource)->where('name', $name)->value('id');
        $programme = fn (string $title) => Programme::where('title', $title)->value('id');

        $resources = [
            [
                'title' => 'Internet Governance: Why the Multistakeholder Approach Works',
                'description' => 'An Internet Society paper explaining the multistakeholder model and why it is essential to an open, globally connected Internet.',
                'type' => ResourceType::PolicyBrief, 'author' => 'Internet Society',
                'external_url' => 'https://www.internetsociety.org/resources/', 'category_id' => $topic('Internet Governance'),
                'programme_id' => $programme('Internet Governance'), 'is_featured' => true, 'months_ago' => 2,
            ],
            [
                'title' => 'Community Networks: A Practical Introduction',
                'description' => 'A guide to what community networks are, how they are built and sustained, and how communities can get started.',
                'type' => ResourceType::Guide, 'author' => 'Internet Society',
                'external_url' => 'https://www.internetsociety.org/issues/community-networks/', 'category_id' => $topic('Community Networks'),
                'programme_id' => $programme('Community Networks'), 'is_featured' => true, 'months_ago' => 4,
            ],
            [
                'title' => 'IoT Security for Policymakers',
                'description' => 'Recommendations for policymakers on improving the security of consumer Internet of Things devices.',
                'type' => ResourceType::Report, 'author' => 'Internet Society',
                'external_url' => 'https://www.internetsociety.org/resources/', 'category_id' => $topic('Internet of Things'),
                'programme_id' => $programme('Internet of Things'), 'months_ago' => 6,
            ],
            [
                'title' => 'Phishing Awareness Workshop Slides',
                'description' => 'Slides from the ISOC Jamaica Online Phishing Awareness Workshop, with examples and a checklist you can share.',
                'type' => ResourceType::Presentation, 'author' => 'ISOC Jamaica',
                'external_url' => 'https://www.internetsociety.org/resources/', 'category_id' => $topic('Cyber Security'),
                'programme_id' => $programme('Cyber Security'), 'is_featured' => true, 'months_ago' => 1,
            ],
            [
                'title' => 'Encryption Brief: What Is Encryption and Why It Matters',
                'description' => 'A short, plain-language explainer on encryption and the risks of proposals to weaken it.',
                'type' => ResourceType::PolicyBrief, 'author' => 'Internet Society',
                'external_url' => 'https://www.internetsociety.org/issues/encryption/', 'category_id' => $topic('Cyber Security'),
                'programme_id' => $programme('Internet Governance'), 'months_ago' => 8,
            ],
            [
                'title' => 'ISOC Jamaica Member Handbook',
                'description' => 'Everything members need to know: how the Chapter is organised, how to join working groups and how to represent the Chapter.',
                'type' => ResourceType::Guide, 'author' => 'ISOC Jamaica', 'category_id' => $topic('Chapter Documents'),
                'is_members_only' => true, 'file' => 'isoc-jamaica-member-handbook.txt', 'months_ago' => 1,
            ],
            [
                'title' => 'Board Meeting Minutes Template',
                'description' => 'Template used for recording Chapter board and general meeting minutes.',
                'type' => ResourceType::Toolkit, 'author' => 'ISOC Jamaica', 'category_id' => $topic('Chapter Documents'),
                'is_members_only' => true, 'file' => 'meeting-minutes-template.txt', 'months_ago' => 3,
            ],
        ];

        foreach ($resources as $data) {
            $file = $data['file'] ?? null;
            $monthsAgo = $data['months_ago'];
            unset($data['file'], $data['months_ago']);

            $resource = Resource::firstOrNew(['title' => $data['title']]);
            $resource->fill([...$data, 'is_published' => true, 'published_on' => now()->subMonths($monthsAgo)]);

            if ($file) {
                $path = 'resources/'.$file;
                Storage::disk(Resource::DISK)->put($path, "{$data['title']}\n\n{$data['description']}\n\n(Sample file. Replace with the real document from the admin dashboard.)\n");
                $resource->file_path = $path;
                $resource->file_name = $file;
                $resource->file_size = Storage::disk(Resource::DISK)->size($path);
            }

            $resource->save();
        }
    }
}
