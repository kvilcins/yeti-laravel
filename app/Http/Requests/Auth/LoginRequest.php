<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        app()->setLocale('en');
        \Log::info('Locale set in prepareForValidation: ' . app()->getLocale());
    }

    public function rules()
    {
        return [
            'email' => [
                'required',
                'string',
                'max:255',
                'exists:users,email',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/', $value)) {
                        $fail('The email address must contain an @ symbol, followed by a domain name and a valid domain zone (e.g., .com, .org).');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Please enter your email address.',
            'email.string' => 'The email address must be a string.',
            'email.max' => 'The email address may not be greater than :max characters.',
            'email.exists' => 'No account found with this email address.',

            'password.required' => 'Please enter your password.',
            'password.string' => 'The password must be a string.',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'email address',
            'password' => 'password',
        ];
    }
}
