<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

class DataController extends Controller
{
    public function getCommonData($slug = null)
    {
        $isAuth = Auth::check();

        if ($isAuth) {
            $user = Auth::user();
            $userName = $user->name;
            $userAvatar = $user->avatar ?? 'img/default-avatar.jpg';
        } else {
            $userName = 'Гость';
            $userAvatar = 'img/default-avatar.jpg';
        }

        $categories = Category::all();
        $ads = $this->getFilteredLots($slug);
        $categoryName = null;

        if ($slug) {
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                $categoryName = $category->name;
            }
        }

        $perPage = 12;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $ads->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedAds = new LengthAwarePaginator(
            $currentItems,
            $ads->count(),
            $perPage,
            $currentPage,
            ['path' => Request::url(), 'query' => Request::query()]
        );

        return [
            'is_auth' => $isAuth,
            'user_name' => $userName,
            'user_avatar' => $userAvatar,
            'categories' => $categories,
            'ads' => $paginatedAds,
            'category_name' => $categoryName,
            'filters' => $this->getActiveFilters(),
            'price_ranges' => $this->getPriceRanges(),
        ];
    }

    private function getFilteredLots($slug = null)
    {
        $query = Item::with(['category', 'bids']);

        $status_filter = request('status', 'active');
        if ($status_filter === 'all') {
        } else {
            $query->where('status', $status_filter);
        }

        if ($slug) {
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $category_filter = request('category');
        if ($category_filter && !$slug) {
            $query->where('category_id', $category_filter);
        }

        $price_from = request('price_from');
        $price_to = request('price_to');
        $price_range = request('price_range');

        if ($price_range) {
            [$from, $to] = $this->parsePriceRange($price_range);
            if ($from !== null) $price_from = $from;
            if ($to !== null) $price_to = $to;
        }

        $lots = $query->get();

        $lots = $lots->map(function($lot) {
            $lot->current_price = $lot->getCurrentPrice();
            return $lot;
        });

        if ($price_from || $price_to) {
            $lots = $lots->filter(function($lot) use ($price_from, $price_to) {
                $currentPrice = $lot->current_price;

                if ($price_from && $currentPrice < $price_from) {
                    return false;
                }

                if ($price_to && $currentPrice > $price_to) {
                    return false;
                }

                return true;
            });
        }

        return $this->applySorting($lots);
    }

    private function applySorting($lots)
    {
        $sort = request('sort', 'time_asc');
        $now = Carbon::now();

        switch ($sort) {
            case 'price_asc':
                return $lots->sortBy('current_price')->values();

            case 'price_desc':
                return $lots->sortByDesc('current_price')->values();

            case 'name_asc':
                return $lots->sortBy('title')->values();

            case 'name_desc':
                return $lots->sortByDesc('title')->values();

            case 'time_desc':
                return $lots->sortBy(function ($lot) use ($now) {
                    return [
                        Carbon::parse($lot->timer)->isPast() ? 1 : 0,
                        -Carbon::parse($lot->timer)->timestamp,
                    ];
                })->values();

            case 'time_asc':
            default:
                return $lots->sortBy(function ($lot) use ($now) {
                    return [
                        Carbon::parse($lot->timer)->isPast() ? 1 : 0,
                        Carbon::parse($lot->timer)->timestamp,
                    ];
                })->values();
        }
    }

    private function parsePriceRange($range)
    {
        switch ($range) {
            case 'under_100':
                return [null, 100];
            case '100_500':
                return [100, 500];
            case '500_1000':
                return [500, 1000];
            case '1000_5000':
                return [1000, 5000];
            case 'over_5000':
                return [5000, null];
            default:
                return [null, null];
        }
    }

    private function getActiveFilters()
    {
        return [
            'sort' => request('sort', 'time_asc'),
            'category' => request('category'),
            'status' => request('status', 'active'),
            'price_from' => request('price_from'),
            'price_to' => request('price_to'),
            'price_range' => request('price_range'),
        ];
    }

    private function getPriceRanges()
    {
        return [
            'under_100' => 'Under $100',
            '100_500' => '$100 - $500',
            '500_1000' => '$500 - $1,000',
            '1000_5000' => '$1,000 - $5,000',
            'over_5000' => 'Over $5,000',
        ];
    }

    public function getLotData($id)
    {
        $lot = Item::with('category')->find($id);

        if (!$lot) {
            return null;
        }

        return [
            'lot' => $lot,
        ];
    }
}
