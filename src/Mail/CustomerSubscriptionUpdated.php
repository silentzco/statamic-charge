<?php

namespace Silentz\Charge\Mail;

use Illuminate\Support\Arr;

class CustomerSubscriptionUpdated extends SubscriptionMailable
{
    protected $templateSetting = 'charge.emails.subscription_updated.template';

    public function build()
    {
        return $this
            ->subject(config('charge.emails.subscription_updated.subject'))
            ->view($this->template(), [
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
                ),
            ]);
    }
}
