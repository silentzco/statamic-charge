<?php

namespace Silentz\Charge\Http\Requests;

class SubscriptionRequest extends ChargeRequest
{
    public function authorize()
    {
        if (! $user = $this->user()) {
            return false;
        }

        return $user->subscribed($this->name);
    }

    protected function failedAuthorization()
    {

    }

    public function rules()
    {
        return [
        ];
    }
}
