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
        // Восстанавливаем страницы
        $pages = Config::get('pages', []);
        
        foreach ($pages as $page) {
            $existingPage = Page::find($page['id']);
            if (!$existingPage) {
                Page::create($page);
                echo "Страница {$page['name']} успешно восстановлена.\n";
            } else {
                $existingPage->update($page);
                echo "Страница с id {$page['id']} обновлена.\n";
            }
        }
        
        // Загружаем данные из конфигурации для категорий
        $categories = Config::get('categories', []);
        
        if (empty($categories)) {
            echo "Данные о категориях не найдены в конфиге!\n";
        } else {
            // Восстанавливаем категории в таблицу
            foreach ($categories as $category) {
                // Если slug не задан, генерируем его
                $category['slug'] = $category['slug'] ?? Str::slug($category['name']);
        
                $existingCategory = Category::where('class', $category['class'])->first();
                if (!$existingCategory) {
                    Category::create([
                        'name' => $category['name'],
                        'class' => $category['class'],
                        'slug' => $category['slug'],
                    ]);
                    echo "Категория {$category['name']} успешно восстановлена.\n";
                } else {
                    echo "Категория с классом {$category['class']} уже существует, пропускаем.\n";
                }
            }
        }
        
        // Загружаем данные из конфигурации для товаров
        $items = Config::get('items', []);
        
        if (empty($items)) {
            echo "Данные о товарах не найдены в конфиге!\n";
        } else {
            // Восстанавливаем товары в таблицу
            foreach ($items as $item) {
                // Если slug не задан, генерируем его
                $item['slug'] = $item['slug'] ?? Str::slug($item['title']);
        
                $existingItem = Item::where('title', $item['title'])->first();
                if (!$existingItem) {
                    // Находим категорию по id категории
                    $category = Category::find($item['category_id']); // Используем category_id для поиска
            
                    if ($category) {
                        Item::create([
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
                    echo "Товар с названием {$item['title']} уже существует, пропускаем.\n";
                }
            }
        }
        
        // Восстанавливаем пользователей
        $users = Config::get('userdata', []);
        
        foreach ($users as $user) {
            $existingUser = User::where('email', $user['email'])->first(); // Проверка по email для уникальности
            if (!$existingUser) {
                User::create([
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'password' => $user['password'], // Сохраняем хеш пароля
                ]);
                echo "Пользователь {$user['name']} успешно восстановлен.\n";
            } else {
                echo "Пользователь с email {$user['email']} уже существует, пропускаем.\n";
            }
        }
    
        // Восстанавливаем ставки
        $bids = Config::get('bids', []);
    
        if (empty($bids)) {
            echo "Данные о ставках не найдены в конфиге!\n";
        } else {
            foreach ($bids as $bid) {
                // Проверяем, существует ли ставка с таким ID
                $existingBid = Bid::find($bid['id']);
                if (!$existingBid) {
                    Bid::create([
                        'id' => $bid['id'],
                        'lot_id' => $bid['lot_id'],
                        'user_id' => $bid['user_id'],
                        'bid_amount' => $bid['bid_amount'],
                        'bid_time' => $bid['bid_time'],
                    ]);
                    echo "Ставка с ID {$bid['id']} успешно восстановлена.\n";
                } else {
                    echo "Ставка с ID {$bid['id']} уже существует, пропускаем.\n";
                }
            }
        }
    }
}
