<?php

namespace Silentz\Charge\Mail;

use Silentz\Charge\Models\User;
use Laravel\Cashier\Subscription;

abstract class SubscriptionMailable extends BaseMailable
{
    /** @var User */
    protected $user;

    /** @var Subscription */
    protected $subscription;

    public function __construct($payload = [])
    {
        parent::__construct($payload);

        $this->subscription = Subscription::where('stripe_id', $this->data['id'])->first();

        $this->user = $this->subscription->user();
    }
}
