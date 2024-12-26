<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        // Получаем категории из конфигурации
        $categories = config('categories');
        
        foreach ($categories as $category) {
            // Используем updateOrCreate для предотвращения дублирования
            Category::updateOrCreate(
                ['name' => $category['name']], // Уникальный идентификатор - 'name'
                ['class' => $category['class']] // Обновляем только поле 'class'
            );
        }
    }
}
