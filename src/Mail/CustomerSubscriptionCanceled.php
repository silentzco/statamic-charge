<?php

namespace Silentz\Charge\Mail;

use Statamic\Support\Arr;

class CustomerSubscriptionCanceled extends SubscriptionMailable
{
    protected $templateSetting = 'charge.emails.subscription_canceled.template';

    public function build()
    {
        return $this
            ->subject(config('charge.email.subscription_canceled.subject'))
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
