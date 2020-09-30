<?php

namespace Silentz\Charge\Mail;

class CustomerUpdated extends CustomerMailable
{
    protected $templateSetting = 'charge.emails.customer_updated.template';

    public function build()
    {
        return $this
            ->subject(config('charge.emails.customer.updated_subject'))
            ->view($this->template(), $this->user->toArray());
    }
}
