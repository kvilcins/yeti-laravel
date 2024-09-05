<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;

class LotController extends Controller
{
    // Метод для отображения формы и обработки отправки данных
    public function handleForm(Request $request)
    {
        if ($request->isMethod('post')) {
            // Обработка данных формы
            $validatedData = $request->validate([
                'lot-name' => 'required|string|max:255',
                'category' => 'required|string',
                'message' => 'required|string',
                'lot-img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'lot-rate' => 'required|numeric|min:0',
                'lot-step' => 'required|numeric|min:0',
                'lot-date' => 'required|date|after:today',
            ]);
            
            // Обработка изображения
            if ($request->hasFile('lot-img')) {
                $imageName = time() . '.' . $request->file('lot-img')->extension();
                $request->file('lot-img')->move(public_path('img'), $imageName);
                $validatedData['lot-img'] = 'img/' . $imageName;
            }
            
            // Сохранение данных в БД
            $categoryId = Category::where('name', $request->input('category'))->first()->id;
            Item::create([
                'title' => $validatedData['lot-name'],
                'description' => $validatedData['message'],
                'price' => $validatedData['lot-rate'],
                'min_bid' => $validatedData['lot-rate'],
                'img' => $validatedData['lot-img'],
                'category_id' => $categoryId,
                'end_date' => $validatedData['lot-date']
            ]);
            
            return redirect()->route('add.form')->with('success', 'Лот успешно добавлен!');
        }
        
        // Отображение формы
        $categories = Category::all(); // Загружаем категории из базы данных
        
        return view('pages.add', [
            'categories' => $categories,
            'errors' => session('errors', []),
            'submitted_data' => session('submitted_data', [])
        ]);
    }
}


