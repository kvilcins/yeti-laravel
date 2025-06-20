<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = config('categories', []);

        foreach ($categories as $category) {
            $category['slug'] = $category['slug'] ?? Str::slug($category['name']);

            Category::updateOrCreate(
                ['class' => $category['class']],
                [
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                ]
            );
        }
    }
}
