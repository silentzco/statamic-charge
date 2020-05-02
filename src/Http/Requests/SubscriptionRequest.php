<?php

namespace Silentz\Charge\Http\Requests;

class SubscriptionRequest extends ChargeRequest
{
    public function authorize()
    {
        return $this->user()->subscribed($this->name);
    }

    public function rules()
    {
        return [
        ];
    }
}
