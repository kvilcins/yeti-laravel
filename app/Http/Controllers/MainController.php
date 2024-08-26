<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        // Подключение конфигурационных файлов
        $categories = include base_path('config/categories.php');
        $ads = include base_path('config/items.php');
        $bets = include base_path('config/data.php');
        $users = include base_path('config/userdata.php');
        
        // Данные
        $is_auth = (bool) rand(0, 1);
        $user_name = 'Константин';
        $user_avatar = 'img/user.jpg';
        
        // Передача данных в представление
        return view('pages.index', [
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar,
            'categories' => $categories,
            'ads' => $ads,
            'bets' => $bets,
            'users' => $users,
        ]);
    }
}
