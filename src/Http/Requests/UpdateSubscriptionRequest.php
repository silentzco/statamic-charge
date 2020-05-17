<?php

namespace Silentz\Charge\Http\Requests;

class UpdateSubscriptionRequest extends SubscriptionRequest
{
    public function rules()
    {
        return [
            'plan' => 'sometimes|required|string',
            'quantity' => 'sometimes|required|integer',
        ];
    }
}
