<?php

namespace Silentz\Charge\Tags;

class Subscription extends BaseTag
{
    public function setupIntent(): string
    {
        return current_user()->createSetupIntent()->client_secret;
    }

    public function create(): string
    {
        return $this->createForm(
            route('statamic.charge.subscriptions.store'));
    }

    public function cancel(): string
    {
        return $this->createForm(
            route('statamic.charge.subscriptions.destroy', ['subscription' => $this->params->get('id')]),
            [],
            'DELETE'
        );
    }

    public function edit(): string
    {
        return $this->createForm(
            route('statamic.charge.subscriptions.update', ['subscription' => $this->get('id')]),
            [],
            'PATCH'
        );
    }
}
