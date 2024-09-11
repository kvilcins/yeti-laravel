<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Http\Controllers\DataController;

class LotController extends Controller
{
    // Метод для отображения формы и обработки отправки данных
    public function handleForm(Request $request)
    {
        if ($request->isMethod('post')) {
            // Валидация данных формы
            $validatedData = $request->validate([
                'lot-name' => 'required|string|max:255',
                'category' => 'required|string',
                'message' => 'required|string',
                'lot-img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'lot-rate' => 'required|numeric|min:0',
                'lot-step' => 'required|numeric|min:0',
                'lot-date' => 'required|date|after:today',
            ]);
            
            // Поиск категории
            $category = Category::where('name', $request->input('category'))->first();
            if (!$category) {
                return redirect()->back()->withErrors(['category' => 'Категория не найдена'])->withInput();
            }
            
            // Обработка изображения
            if ($request->hasFile('lot-img')) {
                $imageName = uniqid() . '.' . $request->file('lot-img')->extension();
                $request->file('lot-img')->move(public_path('img'), $imageName);
                $validatedData['lot-img'] = 'img/' . $imageName;
            }
            
            // Сохранение данных в таблицу `items`
            Item::create([
                'title' => $validatedData['lot-name'],
                'description' => $validatedData['message'],
                'price' => $validatedData['lot-rate'],
                'min_bid' => $validatedData['lot-step'],
                'img' => $validatedData['lot-img'],
                'category_id' => $category->id,
                'end_date' => $validatedData['lot-date'],
            ]);
            
            return redirect()->route('add.form')->with('success', 'Лот успешно добавлен!');
        }
        
        // Получаем общие данные через DataController
        $commonData = DataController::getCommonData();
        
        return view('pages.add', $commonData);
    }
    
    public function show($id)
    {
        $lot = Item::with('category')->find($id);
        
        if (!$lot) {
            abort(404, 'Лот не найден');
        }
        
        // Получаем общие данные через DataController
        $commonData = DataController::getCommonData();
        
        // Передача данных в представление с lot-id
        return view('pages.lot', array_merge($commonData, [
            'lot' => $lot,
            'id' => $id
        ]));
    }
}
