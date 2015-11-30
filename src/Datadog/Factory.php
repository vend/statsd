<?php

namespace Vend\Statsd\Datadog;

use Vend\Statsd\Factory as BaseFactory;
use Vend\Statsd\Type;

/**
 * Two things this factory does differently from the base one:
 *
 *  - Allows you to pass an array (and/or (semi-)associative array) of string tags to be sent along with the metric
 *  value
 *  - Allows you to use the histogram type (which is an extension to StatsD)
 */
class Factory extends BaseFactory
{
    /**
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @param array  $tags
     * @return Metric
     */
    protected function createMetric($key, $value, $type, array $tags = [])
    {
        return new Metric($key, $value, $type, $tags);
    }

    public function counter($key, $delta = 1, array $tags = [])
    {
        return $this->createMetric($key, $delta, Type::COUNTER, $tags);
    }

    public function increment($key, array $tags = [])
    {
        return $this->counter($key, 1, $tags);
    }

    public function decrement($key, array $tags = [])
    {
        return $this->counter($key, -1, $tags);
    }

    public function gauge($key, $value, array $tags = [])
    {
        return $this->createMetric($key, $value, Type::GAUGE, $tags);
    }

    public function timer($key, $time, array $tags = [])
    {
        return $this->createMetric($key, $time, Type::TIMER, $tags);
    }

    public function set($key, $value, array $tags = [])
    {
        return $this->createMetric($key, $value, Type::SET, $tags);
    }
}
