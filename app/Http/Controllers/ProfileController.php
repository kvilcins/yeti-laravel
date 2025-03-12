<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $dataController;
    protected $breadcrumbsController;

    public function __construct(DataController $dataController, BreadcrumbsController $breadcrumbsController)
    {
        $this->dataController = $dataController;
        $this->breadcrumbsController = $breadcrumbsController;
    }

    public function show($slug)
    {
        $commonData = $this->dataController->getCommonData($slug);

        // Генерация хлебных крошек
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.profile', array_merge($commonData, ['breadcrumbs' => $breadcrumbs]));
    }

    public function edit($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        if (Auth::id() !== $user->id) {
            abort(403);
        }

        $commonData = $this->dataController->getCommonData($slug);
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.profile_edit', array_merge($commonData, ['user' => $user, 'breadcrumbs' => $breadcrumbs]));
    }

    public function update(Request $request, $slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        if (Auth::id() !== $user->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile', ['slug' => $user->slug])
            ->with('success', 'Профиль успешно обновлен!');
    }

    public function deleteAvatar($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        if (Auth::id() !== $user->id) {
            abort(403);
        }

        $user->avatar = null;
        $user->save();

        return redirect()->back()->with('success', 'Аватар удален.');
    }
}
