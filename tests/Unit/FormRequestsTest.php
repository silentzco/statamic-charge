<?php

namespace Silentz\Charge\Tests\Unit;

use Silentz\Charge\Tests\TestCase;
use Statamic\Facades\Permission;

class FormRequestsTest extends TestCase
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
