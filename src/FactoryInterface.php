<?php

namespace Vend\Statsd;

use Psr\Log\LoggerAwareInterface;

interface FactoryInterface extends LoggerAwareInterface
{
    /**
     * Creates an 'counter' metric
     *
     * @param string  $key   The metric(s) to decrement
     * @param integer $delta The delta to add to the each metric
     * @return Metric|null Null returned if the metric fails validation
     */
    public function counter($key, $delta);

    /**
     * Creates an 'increment' metric
     *
     * @param string $key The metric(s) to increment
     * @return Metric|null Null returned if the metric fails validation
     */
    public function increment($key);

    /**
     * Creates a 'decrement' metric
     *
     * @param string $key The metric(s) to decrement
     * @return Metric|null Null returned if the metric fails validation
     */
    public function decrement($key);

    /**
     * Creates a 'gauge' metric
     *
     * @param string $key   The metric(s) to set
     * @param float  $value The value for the stats
     * @return Metric|null Null returned if the metric fails validation
     */
    public function gauge($key, $value);

    /**
     * Creates a 'timer' metric
     *
     * @param string $key  The metric(s) to set
     * @param float  $time The elapsed time (ms) to log
     * @return Metric|null Null returned if the metric fails validation
     */
    public function timer($key, $time);

    /**
     * Creates a 'set' metric
     *
     * @param string $key   The metric(s) to set
     * @param float  $value The value for the stats
     * @return Metric|null Null returned if the metric fails validation
     */
    public function set($key, $value);
}
