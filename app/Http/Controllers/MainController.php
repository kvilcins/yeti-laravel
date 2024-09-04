<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;

class MainController extends Controller
{
    public function index()
    {
        // Получаем данные из базы данных
        $categories = Category::all(); // Получаем все категории
        $ads = Item::all(); // Получаем все лоты
        
        // Данные для примера (можно убрать или заменить на логику аутентификации)
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
        ]);
    }
}
