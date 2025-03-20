<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected $dataController;
    protected $breadcrumbsController;

    public function __construct(DataController $dataController, BreadcrumbsController $breadcrumbsController)
    {
        $this->dataController = $dataController;
        $this->breadcrumbsController = $breadcrumbsController;
    }

    public function show()
    {
        $user = Auth::user(); // Получаем текущего авторизованного пользователя
        $commonData = $this->dataController->getCommonData();
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.profile', array_merge($commonData, [
            'breadcrumbs' => $breadcrumbs,
            'user' => $user, // Передаем данные пользователя
        ]));
    }

    public function edit()
    {
        $user = Auth::user(); // Получаем текущего авторизованного пользователя

        $commonData = $this->dataController->getCommonData();
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.profile_edit', array_merge($commonData, [
            'user' => $user,
            'breadcrumbs' => $breadcrumbs
        ]));
    }

    public function update(Request $request)
    {
        $user = Auth::user(); // Получаем текущего авторизованного пользователя

        // Валидация данных формы
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Обновление имени пользователя
        $user->name = $request->name;

        // Обработка пароля, если он был передан
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Обработка аватара, если файл был передан
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар, если он существует
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }

            // Сохраняем новый аватар
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Сохраняем изменения
        $user->save();

        // Перенаправляем с сообщением об успешном обновлении
        return redirect()->route('profile')
            ->with('success', 'Профиль успешно обновлен!');
    }

    public function deleteAvatar()
    {
        $user = Auth::user(); // Получаем текущего авторизованного пользователя

        // Проверяем, есть ли аватар, и если есть - удаляем его
        if ($user->avatar) {
            Storage::delete('public/' . $user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return redirect()->back()->with('success', 'Аватар удален.');
    }
}
