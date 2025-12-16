<?php

namespace App\Http\Validators;
use Illuminate\Support\Facades\Validator;

abstract class BaseValidator
{
    protected $rules = [];

    public function validate($data, $except = [])
    {
        $rules = [];
        if (!empty($except)) {
            $rules = array_filter(
                $this->rules,
                fn($key) => !in_array($key, $except),
                ARRAY_FILTER_USE_KEY
            );
        } else {
            $rules = $this->rules;
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) throw new \InvalidArgumentException($validator->errors()->first());

        return true;
    }
}
