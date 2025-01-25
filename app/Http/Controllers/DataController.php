<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Item;

class DataController extends Controller
{
    // Метод для получения общих данных
    public function getCommonData($slug = null)
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
        
        // Инициализируем переменные для лотов и имени категории
        $ads = [];
        $categoryName = null;
    
        if ($slug) {
            $category = Category::where('slug', $slug)->first();
        
            if ($category) {
                $categoryName = $category->name;
                $ads = Item::where('category_id', $category->id)->with('category')->paginate(9);
            }
        } else {
            $ads = Item::with('category')->paginate(9);
        }
    
        return [
            'is_auth' => $isAuth,
            'user_name' => $userName,
            'user_avatar' => $userAvatar,
            'categories' => $categories,
            'ads' => $ads,
            'category_name' => $categoryName,
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