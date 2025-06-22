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
        $ads = collect();
        $categoryName = null;

        if ($slug) {
            $category = Category::where('slug', $slug)->first();

            if ($category) {
                $categoryName = $category->name;
                $ads = Item::where('category_id', $category->id)->where('status', 'active')->with('category')->get();
            }
        } else {
            $ads = Item::where('status', 'active')->with('category')->get();
        }

        $now = Carbon::now();
        $ads = $ads->sortBy(function ($lot) use ($now) {
            return [
                Carbon::parse($lot->timer)->isPast() ? 1 : 0,
                Carbon::parse($lot->timer)->timestamp,
            ];
        })->values();

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
