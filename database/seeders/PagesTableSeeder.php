<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        Page::truncate();

        $pages = [
            ['route' => 'category.show', 'name' => 'Category', 'type' => 'category', 'slug' => Str::slug('Category'), 'title' => 'Category'],

            ['route' => 'lot.show', 'name' => 'Lot', 'type' => 'item', 'slug' => Str::slug('Lot'), 'title' => 'Lot'],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['route' => $page['route']],
                $page
            );
        }
    }
}
