<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                $validatedData['avatar'] = 'img/default-avatar.jpg'; // Стандартный аватар
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
        
        // Получение общих данных для отображения на странице регистрации
        $dataController = new DataController();
        $commonData = $dataController->getCommonData();
        
        // Передача данных в представление
        return view('pages.sign-up', $commonData);
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
            
            // Проверка учетных данных
            $user = User::where('email', $validatedData['email'])->first();
            
            if ($user && Hash::check($validatedData['password'], $user->password)) {
                Auth::login($user); // Авторизация пользователя
                return redirect()->route('home')->with('success', 'Вы успешно вошли в систему!');
            } else {
                return redirect()->back()->withErrors([
                    'email' => 'Неверные учетные данные.',
                ]);
            }
        }
        
        // Получение общих данных для отображения на странице логина
        $dataController = new DataController();
        $commonData = $dataController->getCommonData();
        
        // Передача данных в представление страницы логина
        return view('pages.login', $commonData);
    }
    
    // Разлогирование
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Вы успешно вышли из системы!');
    }
}
