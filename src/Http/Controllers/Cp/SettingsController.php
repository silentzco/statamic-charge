<?php

namespace Silentz\Charge\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Silentz\Charge\Configurator\Configurator;
use Statamic\Facades\Role;
use Statamic\Http\Controllers\CP\CpController;
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
        $roles = Role::all()->map(function ($role) {
            return [
                'id' => $role->handle(),
                'title' => $role->title(),
                'handle' => $role->handle(),
            ];
        })->values();

        $settings = config('charge');

        Log::info(json_encode($roles));

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
