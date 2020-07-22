<?php

namespace Silentz\Charge\Concerns;

use Laravel\Cashier\Billable;
use Statamic\Facades\User;
use Statamic\Support\Arr;

trait Chargeable
{
    use Billable;

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
