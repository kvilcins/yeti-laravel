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

        $breadcrumbs[] = ['title' => 'Главная', 'url' => route('home')];

        $this->addRouteBreadcrumbs($currentRouteName, $routeParameters, $breadcrumbs);

        return $breadcrumbs;
    }

    /**
     * Рекурсивное добавление хлебных крошек на основе маршрута
     */
    private function addRouteBreadcrumbs($routeName, $routeParameters, &$breadcrumbs)
    {
        if ($routeName !== 'catalog.all' && !in_array($routeName, [
                'register',
                'login',
                'search',
                'search.suggestions',
                'viewed.lots',
                'lot.create',
                'profile',
            ])) {
            $breadcrumbs[] = [
                'title' => 'Каталог',
                'url' => route('catalog'),
            ];
        }

        if (isset($routeParameters['slug']) && $routeName == 'category.show') {
            $category = Category::where('slug', $routeParameters['slug'])->first();
            if ($category) {
                $breadcrumbs[] = [
                    'title' => $category->name,
                    'url' => route('category.show', ['slug' => $category->slug]),
                ];

                if (isset($routeParameters['id'])) {
                    $this->addLotBreadcrumbs($routeParameters['id'], $breadcrumbs);
                }
            }
        }

        elseif (isset($routeParameters['category_slug']) && isset($routeParameters['slug']) && $routeName == 'lot.show') {
            $this->addLotBreadcrumbs($routeParameters['category_slug'], $routeParameters['slug'], $breadcrumbs);
        }

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
        $category = Category::where('slug', $categorySlug)->first();
        if ($category) {
            $breadcrumbs[] = [
                'title' => $category->name,
                'url' => route('category.show', ['slug' => $category->slug]),
            ];
        }

        $item = Item::where('slug', $lotSlug)->first();
        if ($item) {
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
        if (Route::has($page->route)) {
            return route($page->route, $routeParameters);
        }

        return url($page->slug);
    }
}
