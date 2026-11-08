<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ForumCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Edukasi Phishing',
                'description' => 'Diskusi dan edukasi mengenai phishing, teknik penipuan, serta cara mengenalinya.',
            ],
            [
                'name' => 'Keamanan Digital',
                'description' => 'Berbagi pengetahuan mengenai keamanan internet, privasi, dan perlindungan data.',
            ],
            [
                'name' => 'Tanya Jawab',
                'description' => 'Tempat bertanya dan berdiskusi seputar phishing dan keamanan digital.',
            ],
            [
                'name' => 'Pengalaman Pengguna',
                'description' => 'Berbagi pengalaman ketika menemukan URL mencurigakan atau mengalami penipuan digital.',
            ],
            [
                'name' => 'Berita dan Informasi',
                'description' => 'Membahas berita, perkembangan, dan informasi terbaru mengenai ancaman siber.',
            ],
        ];

        foreach ($categories as $category) {
            ForumCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }
    }
}
