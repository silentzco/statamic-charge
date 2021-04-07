<?php

namespace Silentz\Charge\Tests\Unit\FormRequests;

use JMac\Testing\Traits\AdditionalAssertions;
use Silentz\Charge\Http\Requests\ChargeRequest;
use Silentz\Charge\Tests\TestCase;

class ChargeRequestTest extends TestCase
{
    use AdditionalAssertions;

    // /** @test */
    // public function empty_params_doesnt_cause_error()
    // {
    //     $this->createFormRequest(ChargeRequest::class)
    //         ->setContainer(app())
    //         ->validateResolved();

    //     $this->assertTrue(true);
    // }
}
