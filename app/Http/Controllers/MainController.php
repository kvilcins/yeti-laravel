<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Http\Controllers\DataController;

class MainController extends Controller
{
    // Используем DataController для получения общих данных
    protected $dataController;
    
    public function __construct(DataController $dataController)
    {
        $this->dataController = $dataController;
    }
    
    public function index()
    {
        // Получаем данные из базы данных
        $categories = Category::all(); // Получаем все категории
        $ads = Item::all(); // Получаем все лоты
        
        // Получаем общие данные (включая данные пользователя)
        $commonData = $this->dataController->getCommonData();
        
        // Передача данных в представление
        return view('pages.index', array_merge($commonData, [
            'categories' => $categories,
            'ads' => $ads,
        ]));
    }
}

