<?php

namespace Silentz\Charge\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Silentz\Charge\Tests\TestCase;

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
}
