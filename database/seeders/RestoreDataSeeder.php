<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\Page;
use Illuminate\Support\Facades\Config;
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
                $existingCategory = Category::where('class', $category['class'])->first();
                if (!$existingCategory) {
                    Category::create([
                        'name' => $category['name'],
                        'class' => $category['class'],
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
                $existingItem = Item::where('title', $item['title'])->first();
                if (!$existingItem) {
                    // Находим категорию по id категории
                    $category = Category::find($item['category_id']); // Используем category_id для поиска
                    
                    if ($category) {
                        Item::create([
                            'title' => $item['title'],
                            'description' => $item['description'],
                            'price' => $item['price'],
                            'min_bid' => $item['min_bid'],
                            'img' => $item['img'],
                            'category_id' => $category->id, // Присваиваем ID категории
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
    }
}
