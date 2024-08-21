<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContaBancariaStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|string|max:100',
            'saldo' => 'required|numeric'
        ];
    }
}