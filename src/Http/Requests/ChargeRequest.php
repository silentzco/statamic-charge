<?php

namespace Silentz\Charge\Http\Requests;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;

class ChargeRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(Crypt::decrypt($this->_params));
        $this->offsetUnset('_params');
    }
}
