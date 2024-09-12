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
    
    // Показать главную страницу с лотами
    public function index()
    {
        // Получаем общие данные
        $commonData = $this->dataController->getCommonData();
        
        // Передача данных в представление
        return view('pages.index', $commonData);
    }
}
