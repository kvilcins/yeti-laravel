<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Controllers\DataController;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    protected $dataController;
    protected $breadcrumbsController;

    public function __construct(DataController $dataController, BreadcrumbsController $breadcrumbsController)
    {
        $this->dataController = $dataController;
        $this->breadcrumbsController = $breadcrumbsController;
    }

    public function create()
    {
        $dataController = new DataController();
        $commonData = $dataController->getCommonData();

        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.sign-up', array_merge($commonData, ['breadcrumbs' => $breadcrumbs]));
    }

    public function store(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('lot-img')) {
            $avatarName = uniqid() . '.' . $request->file('lot-img')->extension();
            $request->file('lot-img')->move(public_path('img'), $avatarName);
            $validatedData['avatar'] = 'img/' . $avatarName;
        } else {
            $validatedData['avatar'] = null;
        }

        $user = User::create([
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'name' => $validatedData['name'],
            'contact_details' => $validatedData['message'],
            'avatar' => $validatedData['avatar'],
        ]);

        event(new Registered($user));

        return redirect()->route('verification.notice')
            ->with('success', 'Account created! Please check your email to verify your account.');
    }

    public function showLogin()
    {
        $dataController = new DataController();
        $commonData = $dataController->getCommonData();

        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.login', array_merge($commonData, ['breadcrumbs' => $breadcrumbs]));
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();

        if ($user && Hash::check($validatedData['password'], $user->password)) {
            Auth::login($user);
            return redirect()->route('home')->with('success', 'You have successfully logged in!');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'You have successfully logged out!');
    }
}
