<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Загрузка данных из конфигурационного файла
        $users = config('userdata');
        
        // Вставка данных в таблицу users
        DB::table('users')->insert($users);
    }
}
