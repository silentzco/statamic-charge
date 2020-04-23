<?php

namespace Silentz\Charge\Http\Requests;

class CancelSubscriptionRequest extends SubscriptionRequest
{
    public function rules()
    {
        return [
            'plan' => 'required',
        ];
    }
}
