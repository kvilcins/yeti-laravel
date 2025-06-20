<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DataController;
use App\Http\Controllers\BreadcrumbsController;

class ErrorController extends Controller
{
    protected $dataController;

    public function __construct(DataController $dataController)
    {
        $this->dataController = $dataController;
    }

    public function show404()
    {
        $commonData = $this->dataController->getCommonData();

        return response()->view('errors.404', array_merge($commonData, [
            'is_auth' => auth()->check(),
            'user_name' => auth()->check() ? auth()->user()->name : '',
            'user_avatar' => auth()->check() ? auth()->user()->avatar : ''
        ]), 404);
    }

    public function show500()
    {
        $commonData = $this->dataController->getCommonData();

        return response()->view('errors.500', array_merge($commonData, [
            'is_auth' => auth()->check(),
            'user_name' => auth()->check() ? auth()->user()->name : '',
            'user_avatar' => auth()->check() ? auth()->user()->avatar : ''
        ]), 500);
    }
}
