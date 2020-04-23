<?php

namespace Silentz\Charge\Tags;

class Subscription extends BaseTag
{
    public function create()
    {
        dd($this->content);

        return $this->createForm(route('statamic.charge.subscription.store'));
    }

    public function cancel()
    {
        return $this->createForm(
            route('statamic.charge.subscription.destroy', ['subscription' => $this->get('id')]),
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
