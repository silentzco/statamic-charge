<?php

namespace Silentz\Charge\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Events\WebhookHandled;
use Silentz\Charge\Mail\CustomerSubscriptionCanceled;
use Silentz\Charge\Mail\CustomerSubscriptionCreated;
use Silentz\Charge\Mail\CustomerSubscriptionUpdated;
use Silentz\Charge\Mail\CustomerUpdated;
use Silentz\Charge\Tests\Feature\FeatureTestCase;

class EmailsTest extends FeatureTestCase
{
    private $customer;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set(
            'charge.emails.subscription_updated.template',
            'charge::emails.subscription.updated-template'
        );

        $this->customer = $this->createCustomer();
    }

    /** @test */
    public function email_test()
    {
        $events = [
            'customer.subscription.created' => CustomerSubscriptionCreated::class,
            'customer.subscription.updated' => CustomerSubscriptionUpdated::class,
            'customer.subscription.deleted' => CustomerSubscriptionCanceled::class,
            'customer.updated' => CustomerUpdated::class,
        ];

        Mail::fake();

        foreach ($events as $event => $class) {
            WebhookHandled::dispatch([
                'type'=> $event,
                'data' => [
                    'object' => [
                        'customer' => 'cus_HaZNLvZsFWfLwp',
                    ],
                ],
            ]);

            Mail::assertSent($class);
        }
    }
}
