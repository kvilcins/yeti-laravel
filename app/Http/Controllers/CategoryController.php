<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\DataController;

class CategoryController extends Controller
{
    protected $dataController;
    
    public function __construct(DataController $dataController)
    {
        $this->dataController = $dataController;
    }
    
    public function show($categoryId)
    {
        // Получаем общие данные с фильтрацией по категории
        $commonData = $this->dataController->getCommonData($categoryId);
        
        // Передача данных в представление
        return view('pages.categories', $commonData);
    }
}