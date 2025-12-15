<?php

namespace App\Http\Validators;

class AuthValidator extends BaseValidator
{
    protected $rules = [
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => 'required|string|min:8',
    ];
}
