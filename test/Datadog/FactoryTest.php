<?php

namespace Vend\Statsd\Datadog;

use Vend\Statsd\FactoryInterface;
use Vend\Statsd\FactoryInterfaceTest;

class FactoryTest extends FactoryInterfaceTest
{
    /**
     * @return FactoryInterface
     */
    protected function getFactory()
    {
        return new Factory();
    }
}
