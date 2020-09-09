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

    public function swapPlans($newPlan, $oldPlan = null)
    {
        $roles = collect(config('charge.subscription.roles'));

        User::fromUser($this)
            ->removeRole($this->getRole($roles, $oldPlan))
            ->assignRole($this->getRole($roles, $newPlan))
            ->save();
    }

    private function getRole($roles, $plan)
    {
        return Arr::get($roles->firstWhere('plan', $plan), 'role');
    }
}
