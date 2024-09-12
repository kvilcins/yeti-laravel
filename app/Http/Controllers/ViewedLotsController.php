<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Controllers\DataController;

class ViewedLotsController extends Controller
{
    protected $dataController;
    
    public function __construct(DataController $dataController)
    {
        $this->dataController = $dataController;
    }
    
    // Показать страницу просмотренных лотов
    public function index()
    {
        // Получаем общие данные
        $commonData = $this->dataController->getCommonData();
        
        // Получаем данные о просмотренных лотах из cookies
        $viewedLots = json_decode(request()->cookie('viewed_lots', '[]'), true);
        
        // Если данные не являются массивом, инициализируем как пустой массив
        if (!is_array($viewedLots)) {
            $viewedLots = [];
        }
        
        // Фильтрация массива $viewedLots для получения только целых чисел
        $viewedLots = array_filter($viewedLots, 'is_int');
        
        // Получаем данные лотов по их идентификаторам
        $viewedLotsData = Item::whereIn('id', $viewedLots)->get();
        
        // Передача данных в представление
        return view('pages.viewed-lots', array_merge($commonData, [
            'viewedLotsData' => $viewedLotsData,
        ]));
    }
}
