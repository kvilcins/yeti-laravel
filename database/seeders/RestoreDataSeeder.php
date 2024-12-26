<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Config;

class RestoreDataSeeder extends Seeder
{
    public function run()
    {
        // Загружаем данные из конфигурации для категорий
        $categories = Config::get('categories', []); // Получаем данные о категориях из конфигурационного файла
        
        // Проверяем, что данные о категориях существуют
        if (empty($categories)) {
            echo "Данные о категориях не найдены в конфиге!\n";
        } else {
            // Восстанавливаем категории в таблицу
            foreach ($categories as $category) {
                // Проверяем, существует ли категория с таким же классом
                $existingCategory = Category::where('class', $category['class'])->first();
                if (!$existingCategory) {
                    // Если категории с таким классом нет, создаем её
                    Category::create([
                        'name' => $category['name'],
                        'class' => $category['class'],
                    ]);
                    echo "Категория {$category['name']} успешно восстановлена.\n";
                }
            }
        }
        
        // Загружаем данные из конфигурации для товаров
        $items = Config::get('items', []); // Получаем данные о товарах из конфигурационного файла
        
        // Проверяем, что данные о товарах существуют
        if (empty($items)) {
            echo "Данные о товарах не найдены в конфиге!\n";
        } else {
            // Восстанавливаем товары в таблицу
            foreach ($items as $item) {
                // Проверяем, существует ли товар с таким же названием
                $existingItem = Item::where('title', $item['title'])->first();
                if (!$existingItem) {
                    // Находим категорию по классу
                    $category = Category::where('class', $item['category'])->first(); // Ищем категорию по классу
                    
                    if ($category) {
                        // Если категория найдена, добавляем товар
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
                        echo "Категория с классом {$item['category']} не найдена для товара {$item['title']}.\n";
                    }
                } else {
                    echo "Товар с названием {$item['title']} уже существует, пропускаем.\n";
                }
            }
        }
    }
}
