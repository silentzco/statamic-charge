<?php

use Silentz\Charge\Tests\TestCase;

class FirstTest extends TestCase
{
    public function test_route()
    {
        $this->get('amazing')->assertOk();
    }
}