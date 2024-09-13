<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Controllers\DataController;

class SearchController extends Controller
{
    protected $dataController;
    
    public function __construct(DataController $dataController)
    {
        $this->dataController = $dataController;
    }
    
    public function index(Request $request)
    {
        $searchTerm = $request->input('search', '');
        
        $results = Item::where('title', 'LIKE', '%' . $searchTerm . '%')
                       ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                       ->get();
        
        $commonData = $this->dataController->getCommonData();
        
        return view('pages.search-results', array_merge($commonData, [
            'results' => $results,
            'searchTerm' => $searchTerm
        ]));
    }
    
    public function suggestions(Request $request)
    {
        $query = $request->input('query');
        
        $suggestions = Item::where('title', 'LIKE', '%' . $query . '%')
                           ->limit(5)
                           ->get(['title']);
        
        return response()->json($suggestions);
    }
    
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $results = Item::where('title', 'LIKE', '%' . $searchTerm . '%')
                       ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                       ->get();
        
        $commonData = $this->dataController->getCommonData();
        
        return view('pages.search-results', array_merge($commonData, ['results' => $results, 'searchTerm' => $searchTerm]));
    }
}
