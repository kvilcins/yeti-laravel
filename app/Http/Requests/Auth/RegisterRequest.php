<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'name' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }
    
    /**
     * Customize the error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Введите адрес электронной почты.',
            'email.email' => 'Введите корректный адрес электронной почты.',
            'email.unique' => 'Этот адрес электронной почты уже зарегистрирован.',
            'password.required' => 'Введите пароль.',
            'password.min' => 'Пароль должен содержать не менее 6 символов.',
            'name.required' => 'Введите ваше имя.',
            'name.max' => 'Имя не может превышать 255 символов.',
            'message.required' => 'Введите ваши контактные данные.',
            'avatar.image' => 'Файл аватара должен быть изображением.',
            'avatar.mimes' => 'Аватар должен быть в формате jpeg, png, jpg, gif или svg.',
            'avatar.max' => 'Максимальный размер аватара 2 МБ.',
        ];
    }
}
