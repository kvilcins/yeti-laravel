<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Config;

class BackupDataSeeder extends Seeder
{
    public function run()
    {
        // Получаем все товары из базы данных
        $items = Item::all()->toArray();
        
        // Преобразуем товары в нужный формат
        $formattedItems = array_map(function($item) {
            return [
                'title' => $item['title'],
                'description' => $item['description'],
                'price' => $item['price'],
                'min_bid' => $item['min_bid'],
                'img' => $item['img'],
                'category_id' => $item['category_id'],
            ];
        }, $items);
        
        // Получаем текущие данные конфигурации для товаров
        $existingItems = Config::get('items', []);
        
        // Объединяем старые и новые данные
        $mergedItems = array_merge($existingItems, $formattedItems);
        
        // Сохраняем обновленные товары в конфиг
        Config::set('items', $mergedItems);
        
        // Сохраняем товары в файл конфигурации
        file_put_contents(config_path('items.php'), '<?php return ' . var_export($mergedItems, true) . ';');
        
        // Получаем все категории из базы данных
        $categories = Category::all()->toArray();
        
        // Преобразуем категории в нужный формат
        $formattedCategories = array_map(function($category) {
            return [
                'name' => $category['name'],
                'class' => $category['class'],
            ];
        }, $categories);
        
        // Получаем текущие данные конфигурации для категорий
        $existingCategories = Config::get('categories', []);
        
        // Объединяем старые и новые данные
        $mergedCategories = array_merge($existingCategories, $formattedCategories);
    
        // Получаем все пользователей из базы данных
        $users = User::all()->toArray();
    
        // Получаем текущие данные конфигурации для пользователей
        $existingUsers = Config::get('userdata', []);
    
        // Объединяем старые и новые данные
        $mergedUsers = array_merge($existingUsers, $users);
    
        // Сохраняем обновленные данные пользователей в конфиг
        Config::set('userdata', $mergedUsers);
        
        // Сохраняем обновленные категории в конфиг
        Config::set('categories', $mergedCategories);
        
        // Сохраняем категории в файл конфигурации
        file_put_contents(config_path('categories.php'), '<?php return ' . var_export($mergedCategories, true) . ';');
        
        // Сохраняем данные пользователей в файл конфигурации
        file_put_contents(config_path('userdata.php'), '<?php return ' . var_export($mergedUsers, true) . ';');
    }
}
