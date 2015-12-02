<?php

namespace Vend\Statsd;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class Factory implements FactoryInterface
{
    use LoggerAwareTrait;

    /**
     * Factory constructor
     */
    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @return Metric|null
     */
    protected function createMetric($key, $value, $type)
    {
        if (!$this->validate($key, $value, $type)) {
            return null;
        }

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

    /**
     * Validates the value against the type
     *
     * Most metrics require a numeric value. If the value is empty, this can cause receivers to think the format of
     * the emitted datagrams is invalid. (And, heck, it might be: it's not like etsy/statsd is a formal standard.)
     *
     * We treat these errors silently: we log a message and return null from createMetric(), excluding the metric from
     * being sent via the client.
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @return bool
     */
    protected function validate($key, $value, $type)
    {
        if (in_array($type, [Type::COUNTER, Type::GAUGE, Type::TIMER], true) && !is_numeric($value) && !preg_match('/^(-|\+)?\d+/', $value)) {
            $this->logger->error('Could not emit metric: requires an numeric value', [
                'key'   => $key,
                'value' => $value,
                'type'  => $type,
            ]);

            return false;
        }

        return true;
    }
}
