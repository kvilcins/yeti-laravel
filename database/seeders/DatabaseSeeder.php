<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

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
    }
}
