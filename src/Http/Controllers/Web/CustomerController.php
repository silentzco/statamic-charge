<?php

namespace Silentz\Charge\Http\Controllers\Web;

use App\User;
use Silentz\Charge\Http\Requests\UpdateCustomerRequest;
use Statamic\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function show(): ?User
    {
        return current_user();
    }

    public function update(UpdateCustomerRequest $request): User
    {
        $request->validated();

        $user = current_user();

        $user->updateDefaultPaymentMethod($request->get('payment_method'));

        return $user;
    }
}
