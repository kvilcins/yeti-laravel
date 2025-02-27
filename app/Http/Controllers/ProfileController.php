<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $dataController;
    protected $breadcrumbsController;
    
    public function __construct(DataController $dataController, BreadcrumbsController $breadcrumbsController)
    {
        $this->dataController = $dataController;
        $this->breadcrumbsController = $breadcrumbsController;
    }
    
    public function show($slug)
    {
        $commonData = $this->dataController->getCommonData($slug);
        
        // Генерация хлебных крошек
        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());
        
        // Передача данных и хлебных крошек в представление
        return view('pages.profile', array_merge($commonData, ['breadcrumbs' => $breadcrumbs]));
    }
    
}