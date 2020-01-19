<?php

use Silentz\Charge\Tests\TestCase;

class FirstTest extends TestCase
{
    /** @test */
    public function route_exists()
    {
        $this->get('amazing')->assertOk();
    }
}