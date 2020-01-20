<?php

namespace Silentz\Charge\Tests\Unit;

use Silentz\Charge\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class OneTimeTest extends TestCase
{
    /** @test */
    public function routes_exist()
    {
        $routes = Route::getRoutes();

        $this->assertTrue($routes->hasNamedRoute('statamic.charge.one-time.store'));
    }

    /** @test */
    public function checks_for_required_input()
    {
        $this->post('/!/charge/one-time')->assertSessionHasErrors();
//        $this->post('one-time')
    }
}
