<?php

namespace Vend\Statsd\Datadog;

use Vend\Statsd\MetricInterfaceTest;

class MetricTest extends MetricInterfaceTest
{
    /**
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @return Metric
     */
    protected function getSut($key, $value, $type)
    {
        return new Metric($key, $value, $type);
    }

    public function testTags()
    {
        $metric = $this->getSut('foo', 1, 'c');
        $metric->setTags(['something', 'host' => 'somewhere']);
        $this->assertEquals('foo:1|c|#something,host:somewhere', $metric->getData());
    }
}
