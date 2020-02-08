<?php

namespace Silentz\Charge\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class InvoicePaymentActionRequired extends Mailable
{
    use Queueable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
