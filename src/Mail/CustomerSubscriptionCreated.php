<?php

namespace Silentz\Charge\Mail;

use Illuminate\Support\Arr;

class CustomerSubscriptionCreated extends SubscriptionMailable
{
    public function build()
    {
        return $this->to($this->user->email)
            ->subject(config('charge.email.subscription.created_subject'))
            ->view(
                config('charge.email.subscription.created_template'),
                [
                    'first_name' => $this->user->first_name,
                    'last_name' => $this->user->last_name,
                    'plan' => Arr::get($this->data, 'items.data.0.plan.nickname'),
                    'status' => Arr::get($this->data, 'status'),
                    'cancel_at_period_end' => Arr::get(
                        $this->data,
                        'cancel_at_period_end'
                    ),
                    'current_period_end' => Arr::get(
                        $this->data,
                        'current_period_end'
                    )
                    // @todo trial info?
                ]
            );
    }
}
