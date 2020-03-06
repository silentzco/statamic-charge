<?php

namespace Silentz\Charge\Tags;

class Customer extends BaseTag
{
    public function edit()
    {
        return $this->createForm(
            route('statamic.charge.customer.edit', ['name' => $this->get('name')]),
            [],
            'PATCH'
        );
    }
}
