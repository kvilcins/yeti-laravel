<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],

            'email' => ['required', 'email', 'unique:users,email,' . auth()->id()],
            'current_password_for_email' => [
                function ($attribute, $value, $fail) {
                    if (request('email') !== auth()->user()->email && empty($value)) {
                        $fail('Please enter your current password to change email.');
                    }
                    if (!empty($value) && !Hash::check($value, auth()->user()->password)) {
                        $fail('Current password is incorrect.');
                    }
                }
            ],

            'current_password' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty(request('password')) && empty($value)) {
                        $fail('Please enter your current password.');
                    }
                    if (!empty($value) && !Hash::check($value, auth()->user()->password)) {
                        $fail('Current password is incorrect.');
                    }
                }
            ],
            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && Hash::check($value, auth()->user()->password)) {
                        $fail('New password must be different from current password.');
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email.',
            'email.unique' => 'This email is already taken.',
            'password.min' => 'The password must be at least 6 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
