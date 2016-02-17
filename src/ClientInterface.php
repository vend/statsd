<?php

namespace Vend\Statsd;

/**
 * @mixin Factory
 * @mixin Datadog\Factory
 */
interface ClientInterface
{
    /**
     * Client constructor
     *
     * @param Socket           $socket
     * @param FactoryInterface $factory
     */
    public function __construct(Socket $socket, FactoryInterface $factory);

    /**
     * Enqueues a metric to be sent on the next flush
     *
     * @param MetricInterface $metric
     */
    public function add(MetricInterface $metric);

    /**
     * Flushes the queued metrics
     */
    public function flush();

    /**
     * Clients must forward arbitrary methods to the factory
     *
     * This allows the factory to encapsulate the different types of metric available to a client (because this can
     * change depending on the backend in use).
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments);
}
