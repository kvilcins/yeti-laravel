<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;

class AuthController extends Controller
{
    // Регистрация
    public function handleRegistration(Request $request)
    {
        if ($request->isMethod('post')) {
            // Валидация данных формы
            $validatedData = $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'name' => 'required|string|max:255',
                'message' => 'required|string',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            // Обработка аватара
            if ($request->hasFile('avatar')) {
                $avatarName = uniqid() . '.' . $request->file('avatar')->extension();
                $request->file('avatar')->move(public_path('img'), $avatarName);
                $validatedData['avatar'] = 'img/' . $avatarName;
            } else {
                $validatedData['avatar'] = 'img/default_avatar.jpg'; // Стандартный аватар
            }
            
            // Хэширование пароля и сохранение пользователя
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'name' => $validatedData['name'],
                'contact_details' => $validatedData['message'],
                'avatar' => $validatedData['avatar'],
            ]);
            
            return redirect()->route('login')->with('success', 'Аккаунт успешно зарегистрирован!');
        }
        
        // Данные для примера (если аутентификации нет)
        $is_auth = (bool) rand(0, 1);
        $user_name = 'Константин';
        $user_avatar = 'img/user.jpg';
        
        // Загрузка категорий для формы
        $categories = Category::all();
        
        // Передача данных в представление
        return view('pages.sign-up', [
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar,
            'categories' => $categories,
        ]);
    }
    
    // Авторизация
    public function handleLogin(Request $request)
    {
        if ($request->isMethod('post')) {
            // Валидация данных формы логина
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);
            
            // Здесь надо добавить логику проверки учетных данных, но пока просто возвращает успех
            return redirect()->route('login')->with('success', 'Вы успешно вошли в систему!');
        }
        
        // Пример данных пользователя
        $is_auth = false; // Пользователь не авторизован
        $user_name = 'Гость';
        $user_avatar = 'img/default-avatar.jpg';
        
        // Загрузка категорий для отображения на странице (по аналогии с LotController)
        $categories = Category::all();
        
        // Передача данных в представление страницы логина
        return view('pages.login', [
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar,
            'categories' => $categories,
        ]);
    }
}
