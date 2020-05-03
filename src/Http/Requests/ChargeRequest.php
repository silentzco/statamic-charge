<?php

namespace Silentz\Charge\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class ChargeRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if ($params = $this->input('_params', '')) {
            $this->merge(Crypt::decrypt($params));
            $this->offsetUnset('_params');
        }
    }

    public function rules()
    {
        return [];
    }
}
