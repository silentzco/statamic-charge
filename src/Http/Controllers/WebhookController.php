<?php

namespace Silentz\Charge\Http\Controllers;

use Statamic\Facades\Role;
use Statamic\Facades\User;
use Laravel\Cashier\Cashier;
use Illuminate\Http\Response;
use Statamic\Auth\User as AuthUser;
use Silentz\Charge\Models\User as UserModel;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    protected function handleCustomerSubscriptionCreated(
        array $payload
    ): Response {
        // set roles
        /** @var AuthUser */
        //        $user = User::fromUser(Cashier::findBillable($payload['data']['object']['id']));

        $user = User::fromUser(
            UserModel::where('email', 'add-roles@cashier-test.com')->first()
        );

        $user->assignRole('foo');

        $user->save();

        dd(Role::all());

        return new Response();
    }
}
