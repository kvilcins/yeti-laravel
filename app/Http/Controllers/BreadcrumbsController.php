<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\DataController;

class BreadcrumbsController extends Controller
{
    public function generateBreadcrumbs(Request $request)
    {
        $breadcrumbs = [];
        
        // Добавляет главную страницу
        $breadcrumbs[] = ['title' => 'Главная', 'url' => route('home')];
        
        $currentRouteName = Route::currentRouteName();
        $routeParameters = $request->route()->parameters();
        
        // Логика для категорий
        if (str_contains($currentRouteName, 'category') && isset($routeParameters['categoryId'])) {
            $category = \App\Models\Category::find($routeParameters['categoryId']);
            if ($category) {
                $breadcrumbs[] = ['title' => $category->name, 'url' => route('category.show', $category->id)];
            }
        }
        
        // Логика для лотов
        elseif (str_contains($currentRouteName, 'lot') && isset($routeParameters['id'])) {
            $lot = \App\Models\Item::find($routeParameters['id']);
            if ($lot) {
                $breadcrumbs[] = ['title' => $lot->title, 'url' => route('lot.show', $lot->id)];
            }
        }
        
        // Логика для других страниц (регистрации, авторизации и тд)
        else {
            $pageTitle = $this->getDynamicPageTitle($currentRouteName);
            if ($pageTitle) {
                $breadcrumbs[] = ['title' => $pageTitle, 'url' => url()->current()];
            }
        }
        
        return $breadcrumbs;
    }
    
    protected function addPageBreadcrumb($page, &$breadcrumbs)
    {
        // Добавляем текущую страницу в хлебные крошки
        $breadcrumbs[] = ['title' => $page->name, 'url' => route($page->route)];
        
        // Если у страницы есть родитель
        if ($page->parent_id) {
            $parentPage = \App\Models\Page::find($page->parent_id);
            $this->addPageBreadcrumb($parentPage, $breadcrumbs);
        }
    }
}
