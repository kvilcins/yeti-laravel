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
            ['route' => 'category.show', 'name' => 'Категория', 'type' => 'category', 'slug' => Str::slug('Категория'), 'title' => 'Категория'],

            ['route' => 'lot.show', 'name' => 'Лот', 'type' => 'item', 'slug' => Str::slug('Лот'), 'title' => 'Лот'],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['route' => $page['route']],
                $page
            );
        }
    }
}
