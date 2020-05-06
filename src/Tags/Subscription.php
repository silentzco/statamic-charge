<?php

namespace Silentz\Charge\Tags;

use Illuminate\Support\Facades\Auth;
use Silentz\Charge\Models\User;

class Subscription extends BaseTag
{
    public function setupIntent()
    {
        /** @var User */
        $user = Auth::user();

        return $user->createSetupIntent()->client_secret;
    }

    public function create()
    {
        return $this->createForm(
            route('statamic.charge.subscriptions.store'));
    }

    public function cancel()
    {
        return $this->createForm(
            route('statamic.charge.subscriptions.destroy', ['subscription' => $this->get('id')]),
            [],
            'DELETE'
        );
    }

    public function edit()
    {
        return $this->createForm(
            route('statamic.charge.subscriptions.update', ['subscription' => $this->get('id')]),
            [],
            'PATCH'
        );
    }
}
