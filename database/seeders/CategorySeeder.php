<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            CategoryType::News->value => ['Chapter News', 'Policy & Governance', 'Community', 'Cyber Safety'],
            CategoryType::Event->value => ['Webinar', 'Workshop', 'Training', 'Forum', 'Competition'],
            CategoryType::Resource->value => ['Internet Governance', 'Cyber Security', 'Community Networks', 'Internet of Things', 'Chapter Documents'],
        ];

        foreach ($categories as $type => $names) {
            foreach ($names as $name) {
                Category::firstOrCreate(['type' => $type, 'name' => $name]);
            }
        }
    }
}
