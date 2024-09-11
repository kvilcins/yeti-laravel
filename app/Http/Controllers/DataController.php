<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class DataController extends Controller
{
    // Метод для получения общих данных
    public static function getCommonData()
    {
        $is_auth = Auth::check(); // Проверка аутентификации
        
        if ($is_auth) {
            $user = Auth::user();
            $user_name = $user->name;
            $user_avatar = $user->avatar ?? 'img/default-avatar.jpg'; // Если аватар отсутствует, используем стандартный
        } else {
            $user_name = 'Гость';
            $user_avatar = 'img/default-avatar.jpg';
        }
        
        // Получаем категории
        $categories = Category::all();
        
        // Возвращаем данные в виде массива
        return [
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar,
            'categories' => $categories,
        ];
    }
}
