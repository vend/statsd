<?php

namespace Vend\Statsd;

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
