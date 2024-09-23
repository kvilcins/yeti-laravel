<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\DataController;
use App\Http\Controllers\BreadcrumbsController;

class CategoryController extends Controller
{
    protected $dataController;
    protected $breadcrumbsController;
    
    public function __construct(DataController $dataController, BreadcrumbsController $breadcrumbsController)
    {
        $this->dataController = $dataController;
        $this->breadcrumbsController = $breadcrumbsController;
    }
    
    public function show($categoryId)
    {
        // Получаем общие данные с фильтрацией по категории
        $commonData = $this->dataController->getCommonData($categoryId);
        
        // Генерация хлебных крошек
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());
        
        // Передача данных и хлебных крошек в представление
        return view('pages.categories', array_merge($commonData, ['breadcrumbs' => $breadcrumbs]));
    }
}
