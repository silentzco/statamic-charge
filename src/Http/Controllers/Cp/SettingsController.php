<?php

namespace Silentz\Charge\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Silentz\Charge\Configurator\Configurator;
use Statamic\Facades\Folder;
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
            ];
        })->values();

        $settings = config('charge');
        $templates = $this->templates();

        return view(
            'charge::cp.settings',
            compact('plans', 'roles', 'settings', 'templates')
        );
    }

    public function update(Request $request)
    {
        $this->configurator->set('subscription', $request->input('subscription', []));
        $this->configurator->set('email', $request->input('email', []));
        $this->configurator->refresh();

        return back();
    }

    public function templates()
    {
        return collect(Folder::disk('resources')
            ->getFilesRecursively('views'))
            ->map(function ($view) {
                return str_replace_first('views/', '', str_before($view, '.'));
            });
    }
}
