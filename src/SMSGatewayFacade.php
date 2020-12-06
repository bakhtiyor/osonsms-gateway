<?php

namespace OsonSMS\SMSGateway;

use Illuminate\Support\Facades\Facade;

/**
 * @see \OsonSMS\SMSGateway\Skeleton\SkeletonClass
 */
class SMSGatewayFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smsgateway';
    }
}
