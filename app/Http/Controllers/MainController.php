<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DataController;

class MainController extends Controller
{
    protected $dataController;

    public function __construct(DataController $dataController)
    {
        $this->dataController = $dataController;
    }

    public function index()
    {
        $commonData = $this->dataController->getCommonData();

        return view('pages.index', $commonData);
    }
}
