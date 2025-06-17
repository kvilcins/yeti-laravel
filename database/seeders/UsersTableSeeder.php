<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = config('userdata');

        foreach ($users as $user) {
            $existingUser = DB::table('users')->where('email', $user['email'])->first();
            if (!$existingUser) {
                DB::table('users')->insert($user);
                echo "Пользователь {$user['name']} успешно восстановлен.\n";
            } else {
                echo "Пользователь с email {$user['email']} уже существует, пропускаем.\n";
            }
        }
    }
}
