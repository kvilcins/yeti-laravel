<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Item;
use App\Models\Category;

class BreadcrumbsController extends Controller
{
    public function generateBreadcrumbs(Request $request)
    {
        $breadcrumbs = [];
        $currentRouteName = Route::currentRouteName();
        $routeParameters = $request->route()->parameters();
        
        // Добавляем главную страницу
        $breadcrumbs[] = ['title' => 'Главная', 'url' => route('home')];
        
        // Попытка найти страницу в базе данных по имени маршрута
        $page = Page::where('route', $currentRouteName)->first();
        
        if ($page) {
            // Если страница найдена, добавляем её в хлебные крошки
            $breadcrumbs[] = [
                'title' => $page->title ?? $page->name, // Используем title, если оно есть
                'url' => $this->generatePageUrl($page, $routeParameters), // Генерируем URL для страницы
            ];
        } elseif (isset($routeParameters['categoryId'])) {
            // Если это страница категории
            $category = Category::find($routeParameters['categoryId']);
            if ($category) {
                $breadcrumbs[] = [
                    'title' => $category->name,
                    'url' => route('category.show', ['categoryId' => $category->id]),
                ];
            }
        } elseif (isset($routeParameters['id'])) {
            // Если это страница лота
            $item = Item::find($routeParameters['id']);
            if ($item) {
                $breadcrumbs[] = [
                    'title' => $item->title,
                    'url' => route('lot.show', ['id' => $item->id]),
                ];
            }
        }
        
        return $breadcrumbs;
    }
    
    /**
     * Генерация URL для страницы
     */
    private function generatePageUrl(Page $page, $routeParameters)
    {
        // Если у страницы есть явный маршрут, генерируем URL с помощью route
        if (Route::has($page->route)) {
            return route($page->route, $routeParameters);
        }
        
        // В противном случае, формируем URL на основе slug
        return url($page->slug); // Формируем URL напрямую
    }
}