<?php

namespace Silentz\Charge\Http\Controllers\Cp;

use Silentz\Charge\Models\User;
use Statamic\Http\Controllers\CP\CpController;

class CustomerController extends CpController
{
    public function index()
    {
        return view('charge::cp.customers', ['customers' => User::customers()->get()]);
    }
}
