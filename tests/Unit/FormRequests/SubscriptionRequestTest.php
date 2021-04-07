<?php

namespace Silentz\Charge\Tests\Unit\FormRequests;

use JMac\Testing\Traits\AdditionalAssertions;
use Silentz\Charge\Http\Controllers\Web\SubscriptionController;
use Silentz\Charge\Http\Requests\CreateSubscriptionRequest;
use Silentz\Charge\Http\Requests\SubscriptionRequest;
use Silentz\Charge\Http\Requests\UpdateSubscriptionRequest;
use Silentz\Charge\Tests\TestCase;

class SubscriptionRequestTest extends TestCase
{
    use AdditionalAssertions;

    /** @test */
    public function controller_uses_form_requests()
    {
        $this->assertActionUsesFormRequest(SubscriptionController::class, 'store', CreateSubscriptionRequest::class);
        $this->assertActionUsesFormRequest(SubscriptionController::class, 'update', UpdateSubscriptionRequest::class);
        $this->assertActionUsesFormRequest(SubscriptionController::class, 'destroy', SubscriptionRequest::class);
    }

    // /** @test */
    // public function routes_use_form_requests()
    // {
    //    $this->assertRouteUsesFormRequest('statamic.charge.subscriptions.store', CreateSubscriptionRequest::class);
    //     $this->assertRouteUsesFormRequest('statamic.charge.subscriptions.update', UpdateSubscriptionRequest::class);
    //     $this->assertRouteUsesFormRequest('statamic.charge.subscriptions.destroy', SubscriptionRequest::class);
    // }

    /** @test */
    public function controller_uses_middleware()
    {
        $this->assertActionUsesMiddleware(SubscriptionController::class, 'store', 'auth');
        $this->assertActionUsesMiddleware(SubscriptionController::class, 'update', 'auth');
        $this->assertActionUsesMiddleware(SubscriptionController::class, 'destroy', 'auth');
    }

    // /** @test */
    // public function no_user_fails_validation()
    // {
    //     $request = $this->createFormRequest(SubscriptionRequest::class)
    //         ->setContainer(app());

    //     $this->assertFalse($request->authorize());
    // }

    /** @test */
    public function create_subscription_rules_exist()
    {
        $request = new CreateSubscriptionRequest();

        $this->assertEquals(
            [
                'name' => 'required',
                'plan' => 'required',
                'payment_method' => 'required',
                'quantity' => 'sometimes|required|integer',
            ],
            $request->rules()
        );
    }

    /** @test */
    public function update_subscription_rules_exist()
    {
        $request = new UpdateSubscriptionRequest();

        $this->assertEquals(
            [
                'plan' => 'sometimes|required|string',
                'quantity' => 'sometimes|required|integer',
            ],
            $request->rules()
        );
    }
}
