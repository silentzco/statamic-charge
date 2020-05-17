<?php

namespace Silentz\Charge\Tags;

class Subscriptions extends BaseTag
{
    public function index()
    {
        return current_user()->subscriptions()->get()->toArray();
    }
}
