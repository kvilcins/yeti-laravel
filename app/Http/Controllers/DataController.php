<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Item;

class DataController extends Controller
{
    // Метод для получения общих данных
    public function getCommonData()
    {
        $isAuth = Auth::check(); // Проверка аутентификации
        
        if ($isAuth) {
            $user = Auth::user();
            $userName = $user->name;
            $userAvatar = $user->avatar ?? 'img/default-avatar.jpg'; // Если аватар отсутствует, используем стандартный
        } else {
            $userName = 'Гость';
            $userAvatar = 'img/default-avatar.jpg';
        }
        
        // Получаем категории
        $categories = Category::all();
        
        // Получаем все лоты с жадной загрузкой связанных категорий
        $ads = Item::with('category')->get();
        
        // Возвращаем данные в виде массива
        return [
            'is_auth' => $isAuth,
            'user_name' => $userName,
            'user_avatar' => $userAvatar,
            'categories' => $categories,
            'ads' => $ads,
        ];
    }
    
    // Метод для получения данных о конкретном лоте
    public function getLotData($id)
    {
        $lot = Item::with('category')->find($id);
        
        if (!$lot) {
            return null;
        }
        
        return [
            'lot' => $lot,
        ];
    }
}