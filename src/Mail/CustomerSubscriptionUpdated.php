<?php

namespace Silentz\Charge\Mail;

use Illuminate\Support\Arr;

class CustomerSubscriptionUpdated extends SubscriptionMailable
{
    public function build()
    {
        return $this
            ->to($this->user->email)
            ->view(
                config('charge.email.subscription.updated_template'),
                [
                    'plan' => Arr::get($this->data, 'items.data.0.plan.nickname'),
                    'status' => Arr::get($this->data, 'status'),
                    'cancel_at_period_end' => Arr::get($this->data, 'cancel_at_period_end'),
                    'current_period_end' => Arr::get($this->data, 'current_period_end'),
                ]
            );
    }
}