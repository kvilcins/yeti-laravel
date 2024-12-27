<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        // Ищем страницу по slug
        $page = Page::where('slug', $slug)->first();
        
        if ($page) {
            // Передаем страницу в представление, независимо от типа страницы
            return view('page', compact('page')); // Важно, что это один шаблон для всех страниц
        } else {
            abort(404); // Если страница не найдена, вызываем ошибку 404
        }
    }
    
}