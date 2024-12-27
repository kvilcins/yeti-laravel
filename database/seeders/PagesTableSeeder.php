<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        Page::truncate(); // Очищаем таблицу перед заполнением

        $pages = [
            // Динамические страницы (категории и лоты)
            // Для категорий
            ['route' => 'category.show', 'name' => 'Категория', 'type' => 'category', 'slug' => Str::slug('Категория'), 'title' => 'Категория'],

            // Для лотов
            ['route' => 'lot.show', 'name' => 'Лот', 'type' => 'item', 'slug' => Str::slug('Лот'), 'title' => 'Лот'],
        ];

        foreach ($pages as $page) {
            // Обновляем или создаем страницу
            Page::updateOrCreate(
                ['route' => $page['route']], // Уникальность по маршруту
                $page // Данные страницы для вставки или обновления
            );
        }
    }
}
