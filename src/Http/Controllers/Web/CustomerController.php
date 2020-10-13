<?php

namespace Silentz\Charge\Http\Controllers\Web;

use Silentz\Charge\Http\Requests\UpdateCustomerRequest;
use Statamic\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * @return \Silentz\Charge\Chargeable|null
     */
    public function show()
    {
        return current_user();
    }

    /**
     * @return \Silentz\Charge\Chargeable
     */
    public function update(UpdateCustomerRequest $request)
    {
        $request->validated();

        $user = current_user();

        $user->updateDefaultPaymentMethod($request->get('payment_method'));

        return $user;
    }
}
