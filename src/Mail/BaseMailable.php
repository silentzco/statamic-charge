<?php

namespace Silentz\Charge\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

abstract class BaseMailable extends Mailable
{
    use Queueable;

    protected $data;

    public function __construct($payload = [])
    {
        $this->data = $payload['data']['object'];
    }
}
