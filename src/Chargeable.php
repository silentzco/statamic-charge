<?php

namespace Silentz\Charge;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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
        $roles = collect(config('charge.roles_and_plans'));

        User::fromUser($this)
            ->removeRole($this->getRole($roles, $oldPlan))
            ->assignRole($this->getRole($roles, $newPlan))
            ->save();
    }

    private function getRole(Collection $roles, ?string $plan)
    {
        return Arr::get($roles->firstWhere('plan', $plan), 'role');
    }
}
