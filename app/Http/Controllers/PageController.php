<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        // Ищем страницу в БД
        $page = Page::where('slug', $slug)->first();
        
        // Если страница не найдена, возвращаем 404
        if (!$page) {
            abort(404, 'Page not found.');
        }
        
        // Возвращаем стандартный шаблон для всех статичных страниц
        return view('page', [
            'title' => $page->title,
            'content' => $page->content,
            'breadcrumbs' => $page->breadcrumbs,
        ]);
    }
    
}