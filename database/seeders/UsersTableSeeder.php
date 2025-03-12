<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Загружаем данные из конфигурационного файла
        $users = config('userdata');
        
        // Вставка данных в таблицу users с проверкой на уникальность
        foreach ($users as $user) {
            // Проверяем, существует ли пользователь с таким же email
            $existingUser = DB::table('users')->where('email', $user['email'])->first();
            if (!$existingUser) {
                // Если пользователь не существует, добавляем его
                DB::table('users')->insert($user);
                echo "Пользователь {$user['name']} успешно восстановлен.\n";
            } else {
                echo "Пользователь с email {$user['email']} уже существует, пропускаем.\n";
            }
        }
    }
}
