<?php

namespace Silentz\Charge\Tags;

class Subscriptions extends BaseTag
{
    public function list()
    {
        return current_user()->subscriptions()->get()->toArray();
    }
}
