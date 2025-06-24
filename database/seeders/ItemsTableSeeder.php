<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        $items = config('items', []);

        $defaultUser = User::first();

        foreach ($items as $item) {
            $category = Category::find($item['category_id']);

            if ($category && $defaultUser) {
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
                        'user_id' => $item['user_id'] ?? $defaultUser->id,
                        'status' => $item['status'] ?? 'active',
                        'winner_id' => $item['winner_id'] ?? null,
                    ]
                );
            }
        }
    }
}
