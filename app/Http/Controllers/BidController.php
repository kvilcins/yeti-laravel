<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request, $lot)
    {
        // Валидация данных формы
        $validatedData = $request->validate([
            'cost' => 'required|numeric|min:1', // минимальная ставка
        ]);
        
        // Поиск лота
        $item = Item::find($lot);
        
        if (!$item) {
            return redirect()->back()->withErrors(['lot' => 'Лот не найден.']);
        }
        
        // Логика добавления ставки
        Bid::create([
            'lot_id' => $item->id,
            'user_id' => auth()->user()->id, // замените на нужного пользователя
            'price' => $validatedData['cost'],
        ]);
        
        return redirect()->route('lot.show', $item->id)->with('success', 'Ставка успешно сделана!');
    }
}
