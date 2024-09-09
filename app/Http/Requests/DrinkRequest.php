<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DrinkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'string',
            'description' => 'string',
            'photo' => 'file',
            'price' => 'numeric',
        ];
    }
    
}
