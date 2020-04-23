<?php

namespace Silentz\Charge\Http\Controllers\Web;

use Silentz\Charge\Models\User;
use Statamic\Http\Controllers\Controller;
use Silentz\Charge\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    public function show(): ?User
    {
        return current_user();
    }

    public function update(UpdateCustomerRequest $request): User
    {
        $request->validated();
        $pm = $request->get('payment_method');

        current_user()->updateDefaultPaymentMethod($pm);

        return current_user();
    }
}
