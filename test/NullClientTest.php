<?php

namespace Vend\Statsd;

class NullClientTest extends ClientInterfaceTest
{
    protected function getSut(Socket $socket, FactoryInterface $factory)
    {
        return new NullClient($this->socket, $this->factory);
    }
}
