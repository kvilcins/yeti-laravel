<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        Page::truncate(); // Очищаем таблицу перед заполнением
        
        $pages = [
            ['route' => 'home', 'name' => 'Главная'],
            ['route' => 'lot.create', 'name' => 'Добавление лота'],
            ['route' => 'lot.show', 'name' => 'Просмотр лота'],
            ['route' => 'viewed.lots', 'name' => 'Просмотренные лоты'],
            ['route' => 'register', 'name' => 'Регистрация'],
            ['route' => 'login', 'name' => 'Авторизация'],
            ['route' => 'search', 'name' => 'Поиск'],
            ['route' => 'search.suggestions', 'name' => 'Поисковые подсказки'],
            ['route' => 'category.show', 'name' => 'Категория'],
        ];
        
        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
