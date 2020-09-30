<?php

namespace Silentz\Charge\Mail;

use Silentz\Charge\Models\User;

abstract class SubscriptionMailable extends BaseMailable
{
    /** @var User */
    protected $user;

    public function __construct($payload = [])
    {
        parent::__construct($payload);

        $this->user = User::where('stripe_id', $this->data['customer'])->first();
    }

    protected function recipient(): string
    {
        return $this->user->email;
    }
}
