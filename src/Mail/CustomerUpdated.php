<?php

namespace Silentz\Charge\Mail;

use Silentz\Charge\Mail\BaseMailable as Mailable;

class CustomerUpdated extends Mailable
{
    public function build()
    {
        return $this->view('view.name');
    }
}