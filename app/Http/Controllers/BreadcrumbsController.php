<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\Item;
use App\Models\Category;

class BreadcrumbsController extends Controller
{
    private array $routeStructure = [
        'profile.' => ['type' => 'page', 'title' => 'Profile'],
        'profile.edit' => ['type' => 'page', 'title' => 'Edit Profile', 'parent' => 'profile.'],
        'viewed.lots' => ['type' => 'page', 'title' => 'Viewed Items'],
        'lot.create' => ['type' => 'page', 'title' => 'Add Item'],
        'lot.edit' => ['type' => 'page', 'title' => 'Edit Item', 'parent' => 'profile.'],
        'search' => ['type' => 'page', 'title' => 'Search'],
        'catalog' => ['type' => 'page', 'title' => 'Catalog'],
        'category.show' => ['type' => 'category', 'show_catalog' => true],
        'lot.show' => ['type' => 'lot', 'show_catalog' => true],
        'register' => ['type' => 'static', 'title' => 'Sign up'],
        'login' => ['type' => 'static', 'title' => 'Login'],
    ];

    public function generateBreadcrumbs(Request $request): array
    {
        $breadcrumbs = [['title' => 'Home', 'url' => route('home')]];
        $currentRouteName = Route::currentRouteName();
        $routeParameters = $request->route()->parameters();

        $this->buildBreadcrumbs($currentRouteName, $routeParameters, $breadcrumbs);

        return $breadcrumbs;
    }

    private function buildBreadcrumbs(string $routeName, array $routeParameters, array &$breadcrumbs): void
    {
        $config = $this->routeStructure[$routeName] ?? null;

        if (!$config) {
            $this->handlePageFromDatabase($routeName, $routeParameters, $breadcrumbs);
            return;
        }

        if ($config['show_catalog'] ?? false) {
            $catalogPage = $this->getPageByRoute('catalog');
            $breadcrumbs[] = [
                'title' => $catalogPage?->title ?? 'Catalog',
                'url' => route('catalog')
            ];
        }

        if (isset($config['parent'])) {
            $this->buildBreadcrumbs($config['parent'], $routeParameters, $breadcrumbs);
        }

        match ($config['type']) {
            'page' => $this->addPageBreadcrumb($routeName, $config, $breadcrumbs),
            'category' => $this->addCategoryBreadcrumb($routeParameters, $breadcrumbs),
            'lot' => $this->addLotBreadcrumb($routeParameters, $breadcrumbs),
            'static' => $this->addStaticBreadcrumb($routeName, $config, $breadcrumbs),
        };
    }

    private function addPageBreadcrumb(string $routeName, array $config, array &$breadcrumbs, array $routeParameters = []): void
    {
        $page = $this->getPageByRoute($routeName);

        try {
            $url = route($routeName, $routeParameters);
        } catch (\Exception $e) {
            $url = '#';
        }

        $breadcrumbs[] = [
            'title' => $page?->title ?? $config['title'],
            'url' => $url
        ];
    }

    private function addCategoryBreadcrumb(array $routeParameters, array &$breadcrumbs): void
    {
        $slug = $routeParameters['slug'] ?? null;
        if (!$slug) return;

        $category = $this->getCategoryBySlug($slug);
        if ($category) {
            $breadcrumbs[] = [
                'title' => $category->name,
                'url' => route('category.show', ['slug' => $category->slug])
            ];
        }
    }

    private function addLotBreadcrumb(array $routeParameters, array &$breadcrumbs): void
    {
        $categorySlug = $routeParameters['category_slug'] ?? null;
        $lotSlug = $routeParameters['slug'] ?? null;

        if (!$categorySlug || !$lotSlug) return;

        $category = $this->getCategoryBySlug($categorySlug);
        if ($category) {
            $breadcrumbs[] = [
                'title' => $category->name,
                'url' => route('category.show', ['slug' => $category->slug])
            ];
        }

        $item = $this->getItemBySlug($lotSlug);
        if ($item) {
            $breadcrumbs[] = [
                'title' => $item->title,
                'url' => route('lot.show', [
                    'category_slug' => $categorySlug,
                    'slug' => $item->slug
                ])
            ];
        }
    }

    private function addStaticBreadcrumb(string $routeName, array $config, array &$breadcrumbs): void
    {
        $breadcrumbs[] = [
            'title' => $config['title'],
            'url' => route($routeName)
        ];
    }

    private function handlePageFromDatabase(string $routeName, array $routeParameters, array &$breadcrumbs): void
    {
        $page = $this->getPageByRoute($routeName);
        if ($page) {
            $breadcrumbs[] = [
                'title' => $page->title ?? $page->name,
                'url' => route($routeName, $routeParameters)
            ];
        }
    }

    private function getPageByRoute(string $routeName): ?Page
    {
        return Cache::remember("page.route.{$routeName}", 3600, function () use ($routeName) {
            return Page::where('route', $routeName)->first();
        });
    }

    private function getCategoryBySlug(string $slug): ?Category
    {
        return Cache::remember("category.slug.{$slug}", 3600, function () use ($slug) {
            return Category::where('slug', $slug)->first();
        });
    }

    private function getItemBySlug(string $slug): ?Item
    {
        return Cache::remember("item.slug.{$slug}", 1800, function () use ($slug) {
            return Item::where('slug', $slug)->first();
        });
    }
}
