<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Controllers\DataController;

class ViewedLotsController extends Controller
{
    protected $dataController;
    protected $breadcrumbsController;

    public function __construct(DataController $dataController, BreadcrumbsController $breadcrumbsController)
    {
        $this->dataController = $dataController;
        $this->breadcrumbsController = $breadcrumbsController;
    }

    public function index()
    {
        $commonData = $this->dataController->getCommonData();

        $breadcrumbs = $this->breadcrumbsController->generateBreadcrumbs(request());

        $viewedLots = json_decode(request()->cookie('viewed_lots', '[]'), true);

        if (!is_array($viewedLots)) {
            $viewedLots = [];
        }

        $viewedLots = array_filter($viewedLots, 'is_int');

        $viewedLotsData = Item::whereIn('id', $viewedLots)->get();

        return view('pages.viewed-lots', array_merge($commonData, [
            'viewedLotsData' => $viewedLotsData,
            'breadcrumbs' => $breadcrumbs,
        ]));
    }
}
