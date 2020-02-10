<?php

namespace Silentz\Charge\Mail;

class CustomerUpdated extends CustomerMailable
{
    public function build()
    {
        return $this
            ->to($this->email)
            ->view(
                config('charge.email.customer.updated_template'),
                $this->user->toArray()
            );
    }
}