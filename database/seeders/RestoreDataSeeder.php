<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\Page;
use Illuminate\Support\Facades\Config;
use App\Models\Bid;
use Illuminate\Support\Facades\Hash;

class RestoreDataSeeder extends Seeder
{
    public function run()
    {
        $pages = Config::get('pages', []);

        foreach ($pages as $page) {
            $existingPage = Page::find($page['id']);
            if (!$existingPage) {
                Page::create($page);
                echo "Page {$page['name']} successfully restored.\n";
            } else {
                $existingPage->update($page);
                echo "Page with id {$page['id']} updated.\n";
            }
        }

        $categories = Config::get('categories', []);

        if (empty($categories)) {
            echo "Category data not found in config!\n";
        } else {
            foreach ($categories as $category) {
                $category['slug'] = $category['slug'] ?? Str::slug($category['name']);

                $existingCategory = Category::where('class', $category['class'])->first();
                if (!$existingCategory) {
                    Category::create([
                        'name' => $category['name'],
                        'class' => $category['class'],
                        'slug' => $category['slug'],
                    ]);
                    echo "Category {$category['name']} successfully restored.\n";
                } else {
                    echo "Category with class {$category['class']} already exists, skipping.\n";
                }
            }
        }

        $items = Config::get('items', []);

        if (empty($items)) {
            echo "Item data not found in config!\n";
        } else {
            foreach ($items as $item) {
                $item['slug'] = $item['slug'] ?? Str::slug($item['title']);

                $existingItem = Item::where('title', $item['title'])->first();
                if (!$existingItem) {
                    $category = Category::find($item['category_id']);

                    if ($category) {
                        Item::create([
                            'title' => $item['title'],
                            'slug' => $item['slug'],
                            'description' => $item['description'],
                            'price' => $item['price'],
                            'min_bid' => $item['min_bid'],
                            'img' => $item['img'],
                            'category_id' => $category->id,
                            'timer' => $item['timer'] ?? null,
                        ]);
                        echo "Item {$item['title']} successfully restored.\n";
                    } else {
                        echo "Category with id {$item['category_id']} not found for item {$item['title']}.\n";
                    }
                } else {
                    echo "Item with title {$item['title']} already exists, skipping.\n";
                }
            }
        }

        $users = Config::get('userdata', []);

        foreach ($users as $user) {
            $existingUser = User::find($user['id']);
            if (!$existingUser) {
                User::create([
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'password' => $user['password'],
                    'remember_token' => $user['remember_token'] ?? null,
                    'email_verified_at' => $user['email_verified_at'] ?? null,
                    'created_at' => $user['created_at'] ?? null,
                    'updated_at' => $user['updated_at'] ?? null,
                    'contact_details' => $user['contact_details'] ?? null,
                    'avatar' => $user['avatar'] ?? null,
                ]);
                echo "User {$user['name']} successfully restored.\n";
            } else {
                $existingUser->update([
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'contact_details' => $user['contact_details'] ?? null,
                    'avatar' => $user['avatar'] ?? null,
                ]);
                echo "User {$user['name']} updated.\n";
            }
        }

        $bids = Config::get('bids', []);

        if (empty($bids)) {
            echo "Bid data not found in config!\n";
        } else {
            foreach ($bids as $bid) {
                $existingBid = Bid::find($bid['id']);
                if (!$existingBid) {
                    Bid::create([
                        'id' => $bid['id'],
                        'lot_id' => $bid['lot_id'],
                        'user_id' => $bid['user_id'],
                        'bid_amount' => $bid['bid_amount'],
                        'bid_time' => $bid['bid_time'],
                    ]);
                    echo "Bid with ID {$bid['id']} successfully restored.\n";
                } else {
                    echo "Bid with ID {$bid['id']} already exists, skipping.\n";
                }
            }
        }
    }
}
