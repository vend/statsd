<?php

namespace Vend\Statsd;

/**
 * Interface for classes that can make use of a statsd client
 */
interface ClientAwareInterface
{
    public function setStatsdClient(ClientInterface $statsd);
}
