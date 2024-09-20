<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class BreadcrumbsController extends Controller
{
    public function generateBreadcrumbs(Request $request)
    {
        $breadcrumbs = [];
        
        $breadcrumbs[] = ['title' => 'Главная', 'url' => route('home')];
        
        $currentRouteName = Route::currentRouteName();
        $routeParameters = $request->route()->parameters();
        
        if (str_contains($currentRouteName, 'category') !== false && isset($routeParameters['id'])) {
            $category = \App\Models\Category::find($routeParameters['id']);
            if ($category) {
                $breadcrumbs[] = ['title' => $category->name, 'url' => route('category.show', $category->id)];
            }
        } elseif (str_contains($currentRouteName, 'lot') !== false && isset($routeParameters['id'])) {
            $lot = \App\Models\Item::find($routeParameters['id']);
            if ($lot) {
                $breadcrumbs[] = ['title' => $lot->title, 'url' => route('lot.show', $lot->id)];
            }
        } else {
            $pageTitle = $this->getDynamicPageTitle($currentRouteName);
            if ($pageTitle) {
                $breadcrumbs[] = ['title' => $pageTitle, 'url' => url()->current()];
            }
        }
        
        return $breadcrumbs;
    }
}
