<?php

namespace App\Http\Requests\Lot;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Разрешает все запросы
    }
    
    public function rules()
    {
        return [
            'lot-name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'message' => ['required', 'string'],
            'lot-img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'lot-rate' => ['required', 'numeric', 'min:0'],
            'lot-step' => ['required', 'numeric', 'min:0'],
            'lot-date' => ['required', 'date', 'after:today'],
        ];
    }
    
    public function attributes()
    {
        return [
            'lot-name' => 'Название лота',
            'category' => 'Категория',
            'message' => 'Описание',
            'lot-img' => 'Изображение',
            'lot-rate' => 'Цена',
            'lot-step' => 'Шаг ставки',
            'lot-date' => 'Дата окончания',
        ];
    }
}