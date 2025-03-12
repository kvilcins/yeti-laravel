<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataController;
use App\Http\Controllers\BreadcrumbsController;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;

class CatalogController extends Controller
{
    protected $dataController;
    protected $breadcrumbsController;
    
    public function __construct(DataController $dataController, BreadcrumbsController $breadcrumbsController)
    {
        $this->dataController = $dataController;
        $this->breadcrumbsController = $breadcrumbsController;
    }
    
    // Показать главную страницу с лотами
    public function show()
    {
        // Получаем общие данные с фильтрацией по категории
        $commonData = $this->dataController->getCommonData();
        
        // Генерация хлебных крошек
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());
        
        // Передача данных и хлебных крошек в представление
        return view('pages.catalog', array_merge($commonData, ['breadcrumbs' => $breadcrumbs]));
    }
}
