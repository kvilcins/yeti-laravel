<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\Page; // Добавляем модель Page
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
                'id' => $item['id'], // Добавляем id в формат
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
        
        // Убираем дубли из существующих данных и новых данных
        $mergedItems = array_merge($existingItems, $formattedItems);
        $mergedItems = $this->removeDuplicates($mergedItems, 'id');
        
        // Сохраняем обновленные товары в конфиг
        Config::set('items', $mergedItems);
        
        // Сохраняем товары в файл конфигурации
        file_put_contents(config_path('items.php'), '<?php return ' . var_export($mergedItems, true) . ';');
        
        // Получаем все категории из базы данных
        $categories = Category::all()->toArray();
        
        // Преобразуем категории в нужный формат
        $formattedCategories = array_map(function($category) {
            return [
                'id' => $category['id'], // Добавляем id в формат
                'name' => $category['name'],
                'class' => $category['class'],
            ];
        }, $categories);
        
        // Получаем текущие данные конфигурации для категорий
        $existingCategories = Config::get('categories', []);
        
        // Объединяем старые и новые данные, убираем дубли
        $mergedCategories = array_merge($existingCategories, $formattedCategories);
        $mergedCategories = $this->removeDuplicates($mergedCategories, 'id');
        
        // Сохраняем обновленные категории в конфиг
        Config::set('categories', $mergedCategories);
        
        // Сохраняем категории в файл конфигурации
        file_put_contents(config_path('categories.php'), '<?php return ' . var_export($mergedCategories, true) . ';');
    
        // Получаем все пользователи из базы данных
        $users = User::all();
    
        // Получаем текущие данные конфигурации для пользователей
        $existingUsers = Config::get('userdata', []);
    
        // Преобразуем данные пользователей, чтобы все пустые поля были null
        $formattedUsers = $users->map(function($user) {
            return [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'password' => $user->password ?? null, // Если password не существует, ставим null
                'remember_token' => $user->remember_token ?? null,
                'email_verified_at' => $user->email_verified_at ?? null,
                'created_at' => $user->created_at ? $user->created_at->toISOString() : null, // Преобразуем в строку
                'updated_at' => $user->updated_at ? $user->updated_at->toISOString() : null, // Преобразуем в строку
                'contact_details' => $user->contact_details ?? null,
                'avatar' => $user->avatar ?? null,
            ];
        })->toArray();
    
        // Объединяем старые и новые данные, убираем дубли
        $mergedUsers = array_merge($existingUsers, $formattedUsers);
        $mergedUsers = $this->removeDuplicates($mergedUsers, 'id');
    
        // Сохраняем обновленные данные пользователей в конфиг
        Config::set('userdata', $mergedUsers);
    
        // Сохраняем данные пользователей в файл конфигурации
        file_put_contents(config_path('userdata.php'), '<?php return ' . var_export($mergedUsers, true) . ';');
    
        // Получаем все страницы из базы данных
        $pages = Page::all()->toArray();
        
        // Преобразуем страницы в нужный формат
        $formattedPages = array_map(function($page) {
            return [
                'id' => $page['id'],
                'slug' => $page['slug'],
                'name' => $page['name'],
                'title' => $page['title'] ?? null,
                'content' => $page['content'] ?? null,
                'type' => $page['type'] ?? 'default_value',
                'route' => $page['route'] ?? 'default_value',
            ];
        }, $pages);
    
    
        // Получаем текущие данные конфигурации для страниц
        $existingPages = Config::get('pages', []);
        
        // Убедимся, что $existingPages - это массив
        if (!is_array($existingPages)) {
            $existingPages = []; // Если это не массив, то инициализируем как пустой массив
        }
        
        // Объединяем старые и новые данные, убираем дубли
        $mergedPages = array_merge($existingPages, $formattedPages);
        $mergedPages = $this->removeDuplicates($mergedPages, 'id');
        
        // Сохраняем обновленные страницы в конфиг
        Config::set('pages', $mergedPages);
        
        // Сохраняем страницы в файл конфигурации
        file_put_contents(config_path('pages.php'), '<?php return ' . var_export($mergedPages, true) . ';');
    }
    
    // Функция для удаления дублей
    private function removeDuplicates($array, $key)
    {
        $unique = [];
        foreach ($array as $item) {
            // Проверяем наличие ключа перед добавлением
            if (isset($item[$key])) {
                $unique[$item[$key]] = $item;
            }
        }
        return array_values($unique);
    }
}
