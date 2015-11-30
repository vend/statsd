<?php

namespace Vend\Statsd\Datadog;

use Vend\Statsd\MetricInterface;
use Vend\Statsd\MetricInterfaceTest;

class MetricTest extends MetricInterfaceTest
{
    /**
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @return MetricInterface
     */
    protected function getSut($key, $value, $type)
    {
        return new Metric($key, $value, $type);
    }
}
