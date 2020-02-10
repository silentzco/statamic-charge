<?php

namespace Silentz\Charge\Tests\Unit;

use Silentz\Charge\Tests\TestCase;
use Illuminate\Support\Facades\Config;

class EmailsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Config::set(
            'charge.email.subscription.updated_template',
            'charge::emails.subscription.updated-template'
        );
    }

    /** @test */
    public function routes_exist()
    {
        $this->get('csu')
            ->assertOk()
            ->assertSeeText('Test Plan');
    }
}
