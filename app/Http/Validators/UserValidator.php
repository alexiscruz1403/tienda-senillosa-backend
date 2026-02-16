<?php

namespace App\Http\Validators;

class UserValidator extends BaseValidator
{
    protected $rules = [
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone_number' => 'nullable|numeric|max_digits:20',
    ];
}
