<?php

namespace App\Http\Validators;

class PasswordValidator extends BaseValidator
{
    protected $rules = [
        'current_password' => 'required|string|max:255',
        'new_password' => 'required|string|max:255',
        'confirm_password' => 'required|string|max:255|same:new_password',
    ];
}
