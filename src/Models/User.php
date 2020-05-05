<?php

namespace Silentz\Charge\Models;

use Illuminate\Foundation\Auth\User as Model;
use Laravel\Cashier\Billable;
use Statamic\Auth\User as StatamicUser;
use Statamic\Facades\User as UserAPI;
use Statamic\Support\Arr;

class User extends Model
{
    use Billable;

    public function swapPlans($newPlan, $oldPlan = null)
    {
        $roles = collect(config('charge.subscription.roles'));

        /** @var StatamicUser */
        $statamicUser = UserAPI::fromUser($this);

        $statamicUser->removeRole($this->getRole($roles, $oldPlan));
        $statamicUser->assignRole($this->getRole($roles, $newPlan));

        $statamicUser->save();
    }

    private function getRole($roles, $plan)
    {
        return Arr::get($roles->firstWhere('plan', $plan), 'role');
    }
}
