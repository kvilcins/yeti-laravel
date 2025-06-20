<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\Page;
use App\Models\Bid;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class RestoreDataSeeder extends Seeder
{
    public function run()
    {
        $categories = Config::get('categories', []);

        if (empty($categories)) {
            echo "Category data not found in config!\n";
        } else {
            foreach ($categories as $category) {
                $category['slug'] = $category['slug'] ?? Str::slug($category['name']);

                $existingCategory = Category::where('class', $category['class'])->first();
                if (!$existingCategory) {
                    Category::create([
                        'id' => $category['id'],
                        'name' => $category['name'],
                        'class' => $category['class'],
                        'slug' => $category['slug'],
                    ]);
                    echo "Category {$category['name']} successfully restored.\n";
                } else {
                    $existingCategory->update([
                        'name' => $category['name'],
                        'class' => $category['class'],
                        'slug' => $category['slug'],
                    ]);
                    echo "Category with class {$category['class']} updated.\n";
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
                            'id' => $item['id'],
                            'title' => $item['title'],
                            'slug' => $item['slug'],
                            'description' => $item['description'],
                            'price' => $item['price'],
                            'min_bid' => $item['min_bid'],
                            'img' => $item['img'],
                            'category_id' => $category->id,
                            'timer' => $item['timer'],
                        ]);
                        echo "Item {$item['title']} successfully restored.\n";
                    } else {
                        echo "Category with id {$item['category_id']} not found for item {$item['title']}.\n";
                    }
                } else {
                    $updateData = [
                        'title' => $item['title'],
                        'slug' => $item['slug'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'min_bid' => $item['min_bid'],
                        'img' => $item['img'],
                        'category_id' => $item['category_id'],
                    ];

                    $existingItem->update($updateData);
                    echo "Item with title {$item['title']} updated.\n";
                }
            }
        }

        $users = Config::get('userdata', []);

        if (empty($users)) {
            echo "User data not found in config!\n";
        } else {
            foreach ($users as $user) {
                $existingUser = User::where('email', $user['email'])->first();
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
                        'password' => $user['password'],
                        'remember_token' => $user['remember_token'] ?? null,
                        'email_verified_at' => $user['email_verified_at'] ?? null,
                        'created_at' => $user['created_at'] ?? null,
                        'updated_at' => $user['updated_at'] ?? null,
                        'contact_details' => $user['contact_details'] ?? null,
                        'avatar' => $user['avatar'] ?? null,
                    ]);
                    echo "User with email {$user['email']} updated.\n";
                }
            }
        }

        $bids = Config::get('bids', []);

        if (empty($bids)) {
            echo "Bid data not found in config!\n";
        } else {
            foreach ($bids as $bid) {
                $existingBid = Bid::find($bid['id']);
                if (!$existingBid) {
                    $item = Item::find($bid['lot_id']);
                    $user = User::find($bid['user_id']);
                    if ($item && $user) {
                        Bid::create([
                            'id' => $bid['id'],
                            'lot_id' => $bid['lot_id'],
                            'user_id' => $bid['user_id'],
                            'bid_amount' => $bid['bid_amount'],
                            'bid_time' => $bid['bid_time'],
                            'created_at' => $bid['created_at'] ?? null,
                            'updated_at' => $bid['updated_at'] ?? null,
                        ]);
                        echo "Bid with ID {$bid['id']} successfully restored.\n";
                    } else {
                        echo "Bid with ID {$bid['id']} not restored: item or user not found.\n";
                    }
                } else {
                    $existingBid->update([
                        'lot_id' => $bid['lot_id'],
                        'user_id' => $bid['user_id'],
                        'bid_amount' => $bid['bid_amount'],
                        'bid_time' => $bid['bid_time'],
                        'created_at' => $bid['created_at'] ?? null,
                        'updated_at' => $bid['updated_at'] ?? null,
                    ]);
                    echo "Bid with ID {$bid['id']} updated.\n";
                }
            }
        }

        $pages = Config::get('pages', []);

        if (empty($pages)) {
            echo "Page data not found in config!\n";
        } else {
            foreach ($pages as $page) {
                $existingPage = Page::find($page['id']);
                if (!$existingPage) {
                    Page::create([
                        'id' => $page['id'],
                        'slug' => $page['slug'],
                        'name' => $page['name'],
                        'title' => $page['title'] ?? null,
                        'content' => $page['content'] ?? null,
                        'type' => $page['type'] ?? 'default_value',
                        'route' => $page['route'] ?? 'default_value',
                    ]);
                    echo "Page {$page['name']} successfully restored.\n";
                } else {
                    $existingPage->update([
                        'slug' => $page['slug'],
                        'name' => $page['name'],
                        'title' => $page['title'] ?? null,
                        'content' => $page['content'] ?? null,
                        'type' => $page['type'] ?? 'default_value',
                        'route' => $page['route'] ?? 'default_value',
                    ]);
                    echo "Page with id {$page['id']} updated.\n";
                }
            }
        }
    }
}
