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

    public function show()
    {
        $commonData = $this->dataController->getCommonData();

        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        return view('pages.catalog', array_merge($commonData, ['breadcrumbs' => $breadcrumbs]));
    }
}
