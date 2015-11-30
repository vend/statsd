<?php

namespace Vend\Statsd;

interface FactoryInterface
{
    /**
     * Creates an 'counter' metric
     *
     * @param string|array $key   The metric(s) to decrement.
     * @param integer      $delta The delta to add to the each metric
     * @return Metric
     */
    public function counter($key, $delta);

    /**
     * Creates an 'increment' metric
     *
     * @param string|array $key The metric(s) to increment.
     * @return Metric
     */
    public function increment($key);

    /**
     * Creates a 'decrement' metric
     *
     * @param string|array $key The metric(s) to decrement.
     * @return Metric
     */
    public function decrement($key);

    /**
     * Creates a 'gauge' metric
     *
     * @param string|array $key   The metric(s) to set.
     * @param float        $value The value for the stats.
     * @return Metric
     */
    public function gauge($key, $value);

    /**
     * Creates a 'timer' metric
     *
     * @param string|array $key  The metric(s) to set.
     * @param float        $time The elapsed time (ms) to log
     * @return Metric
     */
    public function timer($key, $time);

    /**
     * Creates a 'set' metric
     *
     * This data type acts like a counter, but supports counting of unique occurrences of values between flushes. The
     * backend receives the number of unique events that happened since the last flush.
     *
     * The reference use case involved tracking the number of active and logged in users by sending the current userId
     * of a user with each request with a key of "uniques" (or similar).
     *
     * @param string|array $key   The metric(s) to set.
     * @param float        $value The value for the stats.
     * @return Metric
     */
    public function set($key, $value);
}
