<?php

namespace App\Http\Requests\Lot;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateLotRequest extends FormRequest
{
    public function authorize()
    {
        $lot = $this->route('id') ? \App\Models\Item::find($this->route('id')) : null;

        if (!$lot) {
            return false;
        }

        return auth()->user()->isAdmin() || auth()->user()->isOwnerOf($lot);
    }

    public function rules()
    {
        return [
            'lot_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'message' => ['required', 'string'],
            'lot_img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'lot_rate' => ['required', 'numeric', 'min:0'],
            'lot_step' => ['required', 'numeric', 'min:0'],
            'timer' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function attributes()
    {
        return [
            'lot_name' => 'lot name',
            'category' => 'category',
            'message' => 'description',
            'lot_img' => 'image',
            'lot_rate' => 'price',
            'lot_step' => 'bid step',
            'timer' => 'end date',
        ];
    }

    public function messages()
    {
        return [
            'lot_name.required' => 'The lot name is required.',
            'lot_name.string' => 'The lot name must be a string.',
            'category.required' => 'The category is required.',
            'message.required' => 'The description is required.',
            'lot_img.image' => 'The uploaded file must be an image.',
            'lot_img.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'lot_img.max' => 'The image may not be greater than 2MB.',
            'lot_rate.required' => 'The starting price is required.',
            'lot_rate.numeric' => 'The starting price must be a number.',
            'lot_step.required' => 'The bid step is required.',
            'lot_step.numeric' => 'The bid step must be a number.',
            'timer.date' => 'The end date must be a valid date.',
            'timer.after' => 'The end date must be a date after today.',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('timer') && $this->filled('timer')) {
            $this->merge([
                'timer' => Carbon::parse($this->input('timer'))->startOfDay()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
