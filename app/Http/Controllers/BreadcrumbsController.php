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
        
        // Обрабатываем хлебные крошки в зависимости от маршрута
        $this->addRouteBreadcrumbs($currentRouteName, $routeParameters, $breadcrumbs);
        
        return $breadcrumbs;
    }
    
    /**
     * Рекурсивное добавление хлебных крошек на основе маршрута
     */
    private function addRouteBreadcrumbs($routeName, $routeParameters, &$breadcrumbs)
    {
        // Если это не статичная страница, добавляем "Каталог"
        if ($routeName !== 'catalog.all' && !in_array($routeName, ['register', 'login', 'search', 'search.suggestions', 'viewed-lots'])) {
            $breadcrumbs[] = [
                'title' => 'Каталог',
                'url' => route('catalog'),
            ];
        }
        
        // Если это категория
        if (isset($routeParameters['slug']) && $routeName == 'category.show') {
            $category = Category::where('slug', $routeParameters['slug'])->first();
            if ($category) {
                $breadcrumbs[] = [
                    'title' => $category->name,
                    'url' => route('category.show', ['slug' => $category->slug]),
                ];
                // Если есть лот, добавляем его хлебные крошки
                if (isset($routeParameters['id'])) {
                    $this->addLotBreadcrumbs($routeParameters['id'], $breadcrumbs);
                }
            }
        }

        // Если это страница лота
        elseif (isset($routeParameters['category_slug']) && isset($routeParameters['slug']) && $routeName == 'lot.show') {
            $this->addLotBreadcrumbs($routeParameters['category_slug'], $routeParameters['slug'], $breadcrumbs);
        }
        
        // Для других типов страниц (например, статичных)
        else {
            $page = Page::where('route', $routeName)->first();
            if ($page) {
                $breadcrumbs[] = [
                    'title' => $page->title ?? $page->name,
                    'url' => $this->generatePageUrl($page, $routeParameters),
                ];
            }
        }
    }
    
    /**
     * Добавление хлебных крошек для лота
     */
    private function addLotBreadcrumbs($categorySlug, $lotSlug, &$breadcrumbs)
    {
        // Найдем категорию по slug
        $category = Category::where('slug', $categorySlug)->first();
        if ($category) {
            // Добавляем категорию в хлебные крошки
            $breadcrumbs[] = [
                'title' => $category->name,
                'url' => route('category.show', ['slug' => $category->slug]),
            ];
        }
        
        // Найдем лот по slug
        $item = Item::where('slug', $lotSlug)->first();
        if ($item) {
            // Добавляем сам лот в хлебные крошки
            $breadcrumbs[] = [
                'title' => $item->title,
                'url' => route('lot.show', ['category_slug' => $category->slug, 'slug' => $item->slug]),
            ];
        }
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
