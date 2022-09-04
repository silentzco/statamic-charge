<?php

namespace Silentz\Charge\Mail;

use Silentz\Charge\Mail\BaseMailable as Mailable;

class InvoicePaymentActionRequired extends Mailable
{
    protected $templateSetting = 'charge.emails.invoice_payment_action_required.template';

    public function build()
    {
        return $this
            ->view($this->template());
    }

    protected function recipient(): string
    {
        return '';
    }
}
