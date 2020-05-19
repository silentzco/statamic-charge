<?php

namespace Silentz\Charge\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Statamic\Facades\Role;
use Statamic\Http\Controllers\CP\CpController;
use Statamic\Migrator\Configurator;
use Statamic\Support\Arr;
use Stripe\Plan;
use Stripe\Stripe;

class SettingsController extends CpController
{
    private Configurator $configurator;

    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
        $this->configurator = Configurator::file('charge.php');
    }

    public function show()
    {
        $plans = Arr::get(Plan::all(), 'data', []);
        $roles = Role::all();
        $settings = config('charge');

        return view(
            'charge::cp.settings',
            [
                'plans' => $plans,
                'roles'=> $roles,
                'settings'=>  $settings,
            ]
        );
    }

    public function update(Request $request)
    {
        $rolePlans = $request->rolePlan;
        $this->configurator->set('subscription.roles', $rolePlans);

        return back();
    }
}
