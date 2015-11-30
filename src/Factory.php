<?php

namespace Vend\Statsd;

class Factory implements FactoryInterface
{
    /**
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @return Metric
     */
    protected function createMetric($key, $value, $type)
    {
        return new Metric($key, $value, $type);
    }

    public function counter($key, $delta = 1)
    {
        return $this->createMetric($key, $delta, Type::COUNTER);
    }

    public function increment($key)
    {
        return $this->counter($key, 1);
    }

    public function decrement($key)
    {
        return $this->counter($key, -1);
    }

    public function gauge($key, $value)
    {
        return $this->createMetric($key, $value, Type::GAUGE);
    }

    public function timer($key, $time)
    {
        return $this->createMetric($key, $time, Type::TIMER);
    }

    public function set($key, $value)
    {
        return $this->createMetric($key, $value, Type::SET);
    }
}
