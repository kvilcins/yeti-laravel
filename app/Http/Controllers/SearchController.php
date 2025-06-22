<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataController;
use Illuminate\Http\Request;
use App\Models\Item;

class SearchController extends Controller
{
    protected $dataController;
    protected $breadcrumbsController;

    public function __construct(DataController $dataController, BreadcrumbsController $breadcrumbsController)
    {
        $this->dataController = $dataController;
        $this->breadcrumbsController = $breadcrumbsController;
    }

    public function suggestions(Request $request)
    {
        $query = $request->input('query');

        $suggestions = Item::where('status', 'active')
            ->where('title', 'LIKE', '%' . $query . '%')
            ->limit(5)
            ->get(['title']);

        return response()->json($suggestions);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search', '');

        $results = Item::where('status', 'active')
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            })
            ->with('category')
            ->get();

        $commonData = $this->dataController->getCommonData();
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.search-results', array_merge($commonData, [
            'results' => $results,
            'searchTerm' => $searchTerm,
            'breadcrumbs' => $breadcrumbs
        ]));
    }
}
