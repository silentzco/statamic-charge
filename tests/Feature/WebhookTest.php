<?php

namespace Silentz\Charge\Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Events\WebhookHandled;
use Silentz\Charge\Listeners\HandleWebhook;
use Silentz\Charge\Mail\CustomerSubscriptionCanceled;
use Silentz\Charge\Mail\CustomerSubscriptionCreated;
use Silentz\Charge\Mail\CustomerSubscriptionUpdated;
use Silentz\Charge\Mail\CustomerUpdated;
use Silentz\Charge\Mail\InvoicePaymentActionRequired;

class WebhookTest extends FeatureTestCase
{
    /** @test */
    public function does_respond_to_events()
    {
        $this->mock(HandleWebhook::class, function ($mock) {
            $mock->shouldReceive('handle')->once();
        });

        WebhookHandled::dispatch([]);
    }

    /** @test */
    public function events_do_send_email()
    {
        Mail::fake();

        $types = [
            'customer.subscriptions.created' => CustomerSubscriptionCreated::class,
            'customer.subscriptions.updated' => CustomerSubscriptionUpdated::class,
            'customer.subscriptions.canceled' => CustomerSubscriptionCanceled::class,
            'customer.updated' => CustomerUpdated::class,
            'invoice.payment_action_required' => InvoicePaymentActionRequired::class,
        ];

        foreach ($types as $type => $class) {
            WebhookHandled::dispatch(['type' => $type]);

            Mail::assertSent($class);
        }
    }
}
