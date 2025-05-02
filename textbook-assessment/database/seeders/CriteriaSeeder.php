<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            [
                'name' => 'Accuracy',
                'description' => 'Correctness and precision of information presented in the textbook.',
                'weight' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Relevance',
                'description' => 'Alignment with curriculum standards and learning objectives.',
                'weight' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Readability',
                'description' => 'Clarity of writing, appropriate language level, and logical organization.',
                'weight' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Engagement',
                'description' => 'Ability to capture and maintain student interest through examples, activities, and visuals.',
                'weight' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Inclusivity',
                'description' => 'Representation of diverse perspectives, cultures, and learning styles.',
                'weight' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Visual Quality',
                'description' => 'Effectiveness of diagrams, charts, images, and overall design.',
                'weight' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Practice Opportunities',
                'description' => 'Quantity and quality of exercises, problems, and application activities.',
                'weight' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($criteria as $criterion) {
            Criteria::create($criterion);
        }
    }
}
