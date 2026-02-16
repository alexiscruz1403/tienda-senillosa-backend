<?php

namespace App\Http\Validators;

class AddressValidator extends BaseValidator
{
    protected $rules = [
        'province' => 'required|string|max:100',
        'city' => 'required|string|max:100',
        'street' => 'required|string|max:255',
        'postal_code'=> 'required|string|max:20',
        'department' => 'string|max:100|nullable',
        'additional_info' => 'string|nullable',
    ];
}
