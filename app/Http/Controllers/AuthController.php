<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Controllers\DataController;

class AuthController extends Controller
{
    // Показать форму регистрации
    public function create()
    {
        $dataController = new DataController();
        $commonData = $dataController->getCommonData();
        return view('pages.sign-up', $commonData);
    }
    
    // Сохранение данных регистрации
    public function store(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        
        if ($request->hasFile('avatar')) {
            $avatarName = uniqid() . '.' . $request->file('avatar')->extension();
            $request->file('avatar')->move(public_path('img'), $avatarName);
            $validatedData['avatar'] = 'img/' . $avatarName;
        } else {
            $validatedData['avatar'] = 'img/default-avatar.jpg';
        }
        
        User::create([
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'name' => $validatedData['name'],
            'contact_details' => $validatedData['message'],
            'avatar' => $validatedData['avatar'],
        ]);
        
        return redirect()->route('login')->with('success', 'Аккаунт успешно зарегистрирован!');
    }
    
    // Показать форму входа
    public function showLogin()
    {
        $dataController = new DataController();
        $commonData = $dataController->getCommonData();
        return view('pages.login', $commonData);
    }
    
    // Обработка данных логина
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();
        
        $user = User::where('email', $validatedData['email'])->first();
        
        if ($user && Hash::check($validatedData['password'], $user->password)) {
            Auth::login($user);
            return redirect()->route('home')->with('success', 'Вы успешно вошли в систему!');
        } else {
            return redirect()->back()->withErrors([
                'email' => 'Неверные учетные данные.',
            ]);
        }
    }
    
    // Разлогирование
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Вы успешно вышли из системы!');
    }
}
