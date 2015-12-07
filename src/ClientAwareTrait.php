<?php

namespace Vend\Statsd;

/**
 * Trait for classes that need an optional statsd client
 */
trait ClientAwareTrait
{
    /**
     * @var ClientInterface
     */
    protected $statsd;

    /**
     * @param ClientInterface $statsd
     */
    public function setStatsdClient(ClientInterface $statsd)
    {
        $this->statsd = $statsd;
    }
}
