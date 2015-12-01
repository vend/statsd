<?php

namespace Vend\Statsd;


/**
 * StatsD Client
 *
 * Holds and sends a queue of metric instances to a socket. Uses the provided socket and factory to do so. Exposes
 * methods on the factory via __call, which is a little dynamic for some tastes, but also damn convenient.
 *
 * @method MetricInterface counter(string $key, int $delta)
 * @method MetricInterface increment(string $key)
 * @method MetricInterface decrement(string $key)
 * @method MetricInterface gauge(string $key, int|float $value)
 * @method MetricInterface timer(string $key, float $value)
 * @method MetricInterface set(string $key, mixed $value)
 */
class Client
{
    /**
     * An ordered list of metrics yet to be sent
     *
     * @var MetricInterface[]
     */
    protected $queue = [];

    /**
     * @var Socket
     */
    protected $socket;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * Client constructor
     *
     * @param Socket           $socket
     * @param FactoryInterface $factory
     */
    public function __construct(Socket $socket, FactoryInterface $factory)
    {
        $this->socket = $socket;
        $this->factory = $factory;
    }

    /**
     * Forward methods to the factory
     *
     * @param string $name
     * @param array $arguments
     * @return Metric
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->factory, $name)) {
            $metric = call_user_func_array([$this->factory, $name], $arguments);
            $this->add($metric);
            return $metric;
        }

        throw new \BadMethodCallException('No such StatsD factory method: ' . $name);
    }

    /**
     * Enqueues a metric to be sent on the next flush
     *
     * @param MetricInterface $metric
     */
    public function add(MetricInterface $metric)
    {
        $this->queue[] = $metric;
    }

    /**
     * Flushes the queued metrics
     */
    public function flush()
    {
        $this->socket->open();

        $metrics = array_map(function (MetricInterface $metric) {
            return $metric->getData();
        }, $this->queue);

        $packets = $this->fillPackets($metrics, Socket::MAX_DATAGRAM_SIZE);

        foreach ($packets as $packet) {
            $this->socket->write($packet);
        }

        $this->queue = [];
        $this->socket->close();
    }

    /**
     * Splits an array of pieces of data into combined pieces no larger than the given max
     *
     * @param String[] $data
     * @return array<array<int,string>>
     */
    protected function fillPackets(array $data)
    {
        $maxLength = Socket::MAX_DATAGRAM_SIZE;
        $glue = "\n";

        // The result array of strings, each shorter than $maxLength (unless a piece is larger on its own)
        $result = [''];

        // The index, in the result array, of the piece we're currently appending to
        $index = 0;

        // The size of the current piece
        $size = 0;

        foreach ($data as $metric) {
            $len = strlen($glue . $metric);

            if (($size + $len) > $maxLength) {
                $result[++$index] = $metric; // Fill the next part of the result
                $size = $len;
            } else {
                $result[$index] .= ($size != 0 ? $glue : '') . $metric;
                $size += $len;
            }
        }

        return $result;
    }
}
