<?php

namespace Silentz\Charge\Models;

use Illuminate\Foundation\Auth\User as Model;
use Laravel\Cashier\Billable;
use Statamic\Facades\User as UserAPI;
use Statamic\Support\Arr;

class User extends Model
{
    use Billable;

    public function swapPlans($newPlan, $oldPlan = null)
    {
        $roles = collect(config('charge.subscription.roles'));

        UserAPI::fromUser($this)
            ->removeRole($this->getRole($roles, $oldPlan))
            ->assignRole($this->getRole($roles, $newPlan))
            ->save();
    }

    private function getRole($roles, $plan)
    {
        return Arr::get($roles->firstWhere('plan', $plan), 'role');
    }
}
