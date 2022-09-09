<?php

namespace Silentz\Charge;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Cashier\Billable;
use Statamic\Facades\User;
use Statamic\Support\Arr;

trait Chargeable
{
    use Billable;

    public function scopeCustomers(Builder $query)
    {
        return $query->whereNotNull('stripe_id');
    }

    public function switchToPlan($plan)
    {
        $roles = collect(config('charge.roles_and_plans'));

        User::fromUser($this)
            ->roles(Arr::get($roles->firstWhere('plan', $plan), 'role'))
            ->save();
    }
}
