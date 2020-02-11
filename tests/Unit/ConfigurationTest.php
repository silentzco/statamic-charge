<?php

namespace Silentz\Charge\Tests\Unit;

use Statamic\Facades\Permission;
use Silentz\Charge\Tests\TestCase;

class ConfigurationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function permissions_exist()
    {
        $this->assertNotNull(Permission::get('charge'));
    }
}
