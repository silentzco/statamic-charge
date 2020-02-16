<?php

namespace Silentz\Charge\Tags;

class Subscription extends BaseTag
{
    public function cancel()
    {
        return $this->createForm(
            route('statamic.charge.subscription.cancel', ['name' => $this->get('name')]),
            [],
            'DELETE'
        );
    }
}
