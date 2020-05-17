<?php

namespace Silentz\Charge\Http\Requests;

class SubscriptionRequest extends ChargeRequest
{
    public function authorize()
    {
        if (! $user = $this->user()) {
            return false;
        }

        return $this->subscription->user_id == $user->id;
    }

    public function rules()
    {
        return [];
    }
}
