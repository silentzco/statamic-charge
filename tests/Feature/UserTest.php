<?php

namespace Silentz\Charge\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Statamic\Facades\Role;
use Statamic\Facades\User;

class UserTest extends FeatureTestCase
{
    /** @test */
    public function can_swap_roles()
    {
        Role::make('role-one')->title('Role One')->save();
        Role::make('role-two')->title('Role Two')->save();

        $roles[] = [
            'plan' => 'plan-one',
            'role' => 'role-one',
        ];

        $roles[] = [
            'plan' => 'plan-two',
            'role' => 'role-two',
        ];

        Config::set('charge.roles_and_plans', $roles);

        $user = $this->createCustomer('swap-roles');
        $user->stripe_id = 'swap-roles';
        $user->save();

        $statamicUser = User::find($user->id)->roles('role-one');

        $this->assertTrue($statamicUser->hasRole('role-one'));
        $this->assertFalse($statamicUser->hasRole('role-two'));

        $user->switchToPlan('plan-two');

        $new = User::find($user->id);
        $this->assertFalse($new->hasRole('role-one'));
        $this->assertTrue($new->hasRole('role-two'));
    }
}
