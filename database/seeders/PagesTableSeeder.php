<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        $pages = [
            [
                'route' => 'category.show',
                'name' => 'Category',
                'type' => 'category',
                'slug' => 'category',
                'title' => 'Category'
            ],
            [
                'route' => 'lot.show',
                'name' => 'Lot',
                'type' => 'item',
                'slug' => 'lot',
                'title' => 'Lot'
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['route' => $page['route']],
                $page
            );
        }
    }
}
