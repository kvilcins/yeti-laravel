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
            echo "Данные о категориях не найдены в конфиге!\n";
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
                    echo "Категория {$category['name']} успешно восстановлена.\n";
                } else {
                    $existingCategory->update([
                        'name' => $category['name'],
                        'class' => $category['class'],
                        'slug' => $category['slug'],
                    ]);
                    echo "Категория с классом {$category['class']} обновлена.\n";
                }
            }
        }

        $items = Config::get('items', []);

        if (empty($items)) {
            echo "Данные о товарах не найдены в конфиге!\n";
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
                        ]);
                        echo "Товар {$item['title']} успешно восстановлен.\n";
                    } else {
                        echo "Категория с id {$item['category_id']} не найдена для товара {$item['title']}.\n";
                    }
                } else {
                    $existingItem->update([
                        'title' => $item['title'],
                        'slug' => $item['slug'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'min_bid' => $item['min_bid'],
                        'img' => $item['img'],
                        'category_id' => $item['category_id'],
                    ]);
                    echo "Товар с названием {$item['title']} обновлен.\n";
                }
            }
        }

        $users = Config::get('userdata', []);

        if (empty($users)) {
            echo "Данные о пользователях не найдены в конфиге!\n";
        } else {
            foreach ($users as $user) {
                $existingUser = User::where('email', $user['email'])->first();
                if (!$existingUser) {
                    User::create([
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'name' => $user['name'],
                        'password' => $user['password'], // Password is already hashed in backup
                        'remember_token' => $user['remember_token'] ?? null,
                        'email_verified_at' => $user['email_verified_at'] ?? null,
                        'created_at' => $user['created_at'] ?? null,
                        'updated_at' => $user['updated_at'] ?? null,
                        'contact_details' => $user['contact_details'] ?? null,
                        'avatar' => $user['avatar'] ?? null,
                    ]);
                    echo "Пользователь {$user['name']} успешно восстановлен.\n";
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
                    echo "Пользователь с email {$user['email']} обновлен.\n";
                }
            }
        }

        $bids = Config::get('bids', []);

        if (empty($bids)) {
            echo "Данные о ставках не найдены в конфиге!\n";
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
                        echo "Ставка с ID {$bid['id']} успешно восстановлена.\n";
                    } else {
                        echo "Ставка с ID {$bid['id']} не восстановлена: товар или пользователь не найдены.\n";
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
                    echo "Ставка с ID {$bid['id']} обновлена.\n";
                }
            }
        }

        $pages = Config::get('pages', []);

        if (empty($pages)) {
            echo "Данные о страницах не найдены в конфиге!\n";
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
                    echo "Страница {$page['name']} успешно восстановлена.\n";
                } else {
                    $existingPage->update([
                        'slug' => $page['slug'],
                        'name' => $page['name'],
                        'title' => $page['title'] ?? null,
                        'content' => $page['content'] ?? null,
                        'type' => $page['type'] ?? 'default_value',
                        'route' => $page['route'] ?? 'default_value',
                    ]);
                    echo "Страница с id {$page['id']} обновлена.\n";
                }
            }
        }
    }
}
