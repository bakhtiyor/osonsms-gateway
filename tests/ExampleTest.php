<?php

namespace OsonSMS\SMSGateway\Tests;

use Orchestra\Testbench\TestCase;
use OsonSMS\SMSGateway\SMSGatewayServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [SMSGatewayServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
