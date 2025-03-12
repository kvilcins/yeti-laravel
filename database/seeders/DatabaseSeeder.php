<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use App\Models\Page;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Сначала сохраняем данные в конфиг (категории и товары)
        $this->call(BackupDataSeeder::class);
        
        // Выполняем миграцию
        Artisan::call('migrate:fresh');
        
        // После миграции восстанавливаем данные
        $this->call(RestoreDataSeeder::class);
        
        // Запускаем другие сидеры
        $this->call(CategoriesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        
        // Восстанавливаем страницы из конфигов, если их нет в базе
        $this->restorePages();
    }
    
    private function restorePages()
    {
        $pages = Config::get('pages', []);
        
        foreach ($pages as $page) {
            $existingPage = Page::where('slug', $page['slug'])->first(); // Используем slug для поиска
            if (!$existingPage) {
                // Если страница не существует, добавляем
                Page::create($page);
                echo "Страница {$page['name']} успешно восстановлена.\n";
            } else {
                // Если страница существует, обновляем данные
                $existingPage->update($page);
                echo "Страница с slug {$page['slug']} обновлена.\n";
            }
        }
    }
}
