<?php

namespace Silentz\Charge\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

abstract class BaseMailable extends Mailable
{
    use Queueable;

    protected $data;

    protected $templateSetting;

    public function __construct($payload = [])
    {
        $this->data = $payload['data']['object'];
        $this->from(config('charge.emails.sender'));
    }

    public static function createFromPayload(array $payload)
    {
        return new static($payload);
    }

    public function deliver()
    {
        if (! $this->shouldSend()) {
            return;
        }

        Mail::to($this->recipient())->send($this);
    }

    abstract protected function recipient(): string;

    protected function shouldSend(): bool
    {
        return ! empty(config($this->templateSetting));
    }

    protected function template()
    {
        return config($this->templateSetting);
    }
}
