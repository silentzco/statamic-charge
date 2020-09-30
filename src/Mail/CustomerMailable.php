<?php

namespace Silentz\Charge\Mail;

use App\User;

// use Silentz\Charge\Models\User;

abstract class CustomerMailable extends BaseMailable
{
    /** @var User */
    protected $user;

    protected $templateSetting = 'charge.emails.customer_updated.template';

    public function __construct($payload = [])
    {
        parent::__construct($payload);

        $this->user = User::where('stripe_id', $this->data['id'])->first();
    }

    protected function recipient(): string
    {
        return $this->user->email;
    }
}
