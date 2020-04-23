<?php

namespace Silentz\Charge\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'payment_method' => 'required',
        ];
    }
}
