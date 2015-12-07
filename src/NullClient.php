<?php

namespace Vend\Statsd;

/**
 * A 'null' client
 *
 * Useful for where you only sometimes have access to a statsd client; using a NullClient will allow you to
 * avoid null-checks in your calling code (just assume, for example, that $this->client->increment() etc. will
 * always be available, and default $this->client to an instance of this class)
 *
 * Because this NullClient can't make assumptions about the factory you'll pass in, the __call method will
 * respond to *any* method called on this client.
 */
class NullClient implements ClientInterface
{
    public function __construct(Socket $socket = null, FactoryInterface $factory = null)
    {
    }

    public function add(MetricInterface $metric)
    {
    }

    public function flush()
    {
    }

    /**
     * @param string $name
     * @param array  $arguments
     * @return null
     */
    public function __call($name, $arguments)
    {
        return null;
    }
}
