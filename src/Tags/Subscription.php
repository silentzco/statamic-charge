<?php

namespace Silentz\Charge\Tags;

class Subscription extends BaseTag
{
    public function create()
    {
        return $this->createForm(route('statamic.charge.subscription.store'));
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
