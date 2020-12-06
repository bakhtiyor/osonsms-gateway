<?php

namespace Osonsms\Gateway\Tests;

use Orchestra\Testbench\TestCase;
use Osonsms\Gateway\GatewayServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [GatewayServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
