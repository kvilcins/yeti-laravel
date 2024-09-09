<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\User;

class AuthController extends Controller
{
    public function handleLogin(Request $request)
    {
        if ($request->isMethod('post')) {
            // Обработка POST запроса
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            
            $credentials = $request->only('email', 'password');
            
            if (Auth::attempt($credentials)) {
                return redirect()->intended('home');
            }
            
            return redirect()->back()->withErrors([
                'email' => 'Неверные учетные данные.',
            ]);
        }
        
        // Поиск категории
        $category = Category::where('name', $request->input('category'))->first();
        if (!$category) {
            return redirect()->back()->withErrors(['category' => 'Категория не найдена'])->withInput();
        }
        
        $is_auth = (bool) rand(0, 1);
        $user_name = 'Константин';
        $user_avatar = 'img/user.jpg';
        
        // Загрузка категорий для формы
        $categories = Category::all();
        
        // Обработка GET запроса
        return view('pages.login', [
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar,
            'categories' => $categories,
        ]);
    }
}
