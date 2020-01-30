<?php

namespace Silentz\Charge\Http\Requests;

use Statamic\Facades\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateSubscriptionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'subscription' => 'required',
            'plan' => 'required',
            'payment_method' => 'required',
        ];
    }

    public function getUser()
    {
        return User::find($this->user_id);
    }
}
