<?php

namespace App\Http\Validators;
use Illuminate\Support\Facades\Validator;

abstract class BaseValidator
{
    protected $rules = [];

    public function validate(array $data)
    {
        $validator = Validator::make($data, $this->rules);

        if ($validator->fails()) throw new \InvalidArgumentException($validator->errors()->first());

        return true;
    }
}
