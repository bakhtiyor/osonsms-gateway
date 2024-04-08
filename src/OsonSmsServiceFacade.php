<?php

namespace OsonSMS\SMSGateway;

use Illuminate\Support\Facades\Facade;

class OsonSmsServiceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'osonsmsservice';
    }
}
