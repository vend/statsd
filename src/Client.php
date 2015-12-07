<?php

namespace Vend\Statsd;

/**
 * StatsD Client
 *
 * Holds and sends a queue of metric instances to a socket. Uses the provided socket and factory to do so. Exposes
 * methods on the factory via __call, which is a little dynamic for some tastes, but also damn convenient.
 *
 * @method MetricInterface counter(string $key, int $delta, ...)
 * @method MetricInterface increment(string $key, ...)
 * @method MetricInterface decrement(string $key, ...)
 * @method MetricInterface gauge(string $key, float $value, ...)
 * @method MetricInterface timer(string $key, float $value, ...)
 * @method MetricInterface set(string $key, mixed $value, ...)
 */
class Client implements ClientInterface
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

    public function __call($name, $arguments)
    {
        if (method_exists($this->factory, $name)) {
            $metric = call_user_func_array([$this->factory, $name], $arguments);

            if ($metric) {
                $this->add($metric);
            }

            return $metric;
        }

        throw new \BadMethodCallException('No such StatsD factory method: ' . $name);
    }


    public function add(MetricInterface $metric)
    {
        $this->queue[] = $metric;
    }


    public function flush()
    {
        $this->socket->open();

        $metrics = array_map(function (MetricInterface $metric) {
            return $metric->getData();
        }, $this->queue);

        $packets = $this->fillPackets($metrics);

        foreach ($packets as $packet) {
            $this->socket->write($packet);
        }

        $this->queue = [];
        $this->socket->close();
    }

    /**
     * Splits an array of pieces of data into combined pieces no larger than the given max
     *
     * A few subtleties:
     *   - We attempt to fill each packet as much as possible, up to our preferred maximum size
     *   - If a single string in the input is larger than the maximum size, we still attempt to send it (in a packet on its own)
     *     This will probably be handled just fine right up to a good few KB (maybe even up near 60kB)
     *
     * @param String[] $data
     * @return String[]
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
