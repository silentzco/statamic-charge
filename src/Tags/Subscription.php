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
            route('statamic.charge.subscriptions.destroy', ['name' => $this->get('name')]),
            [],
            'DELETE'
        );
    }

    public function edit()
    {
        $name = $this->get('name');

        return $this->createForm(
            route('statamic.charge.subscription.edit', ['name' => $name]),
            current_user()->subscription($name)->toArray(),
            'PATCH'
        );
    }
}
