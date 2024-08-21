<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContaBancariaTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'from_account_id' => 'required|exists:contas_bancarias,id',
            'to_account_id' => 'required|exists:contas_bancarias,id',
            'amount' => 'required|numeric|min:0.01'
        ];
    }
}