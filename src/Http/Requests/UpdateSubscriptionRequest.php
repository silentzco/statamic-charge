<?php

namespace Silentz\Charge\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'plan' => 'required',
        ];
    }
}
