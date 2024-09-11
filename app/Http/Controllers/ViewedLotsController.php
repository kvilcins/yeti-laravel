<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Controllers\DataController;

class ViewedLotsController extends Controller
{
    public function index()
    {
        // Получаем общие данные через DataController
        $commonData = DataController::getCommonData();
        
        // Получаем данные о просмотренных лотах из cookies
        $viewedLots = json_decode(request()->cookie('viewed_lots', '[]'), true);
        if (!is_array($viewedLots)) {
            $viewedLots = [];
        }
        
        // Фильтрация массива $viewedLots
        $viewedLots = array_filter($viewedLots, 'is_int');
        
        // Получаем данные лотов по их индексам
        $viewedLotsData = Item::whereIn('id', $viewedLots)->get();
        
        // Передача данных в представление
        return view('pages.viewed-lots', array_merge($commonData, [
            'viewedLotsData' => $viewedLotsData,
        ]));
    }
}
