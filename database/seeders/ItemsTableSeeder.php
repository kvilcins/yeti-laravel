<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Str;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        $items = config('items', []);

        foreach ($items as $item) {
            $category = Category::find($item['category_id']);

            if ($category) {
                $item['slug'] = $item['slug'] ?? Str::slug($item['title']);

                Item::updateOrCreate(
                    ['title' => $item['title']],
                    [
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'min_bid' => $item['min_bid'],
                        'img' => $item['img'],
                        'category_id' => $category->id,
                        'slug' => $item['slug'],
                        'timer' => $item['timer'] ?? null,
                    ]
                );
            }
        }
    }
}
