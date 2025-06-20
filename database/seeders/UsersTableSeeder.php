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
                echo "User {$user['name']} successfully restored.\n";
            } else {
                echo "User with email {$user['email']} already exists, skipping.\n";
            }
        }
    }
}
