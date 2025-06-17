<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
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

    public function show($slug)
    {
        $commonData = $this->dataController->getCommonData($slug);

        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.categories', array_merge($commonData, ['breadcrumbs' => $breadcrumbs]));
    }
}
