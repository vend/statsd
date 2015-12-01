<?php

namespace Vend\Statsd;

use \PHPUnit_Framework_TestCase as BaseTest;

abstract class MetricInterfaceTest extends BaseTest
{
    /**
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @return MetricInterface
     */
    abstract protected function getSut($key, $value, $type);

    public function testGetData()
    {
        $metric = $this->getSut('key', 'value', 'c');
        $this->assertEquals($metric->getData(), 'key:value|c');

        $metric = $this->getSut('key', -1, 'c');
        $metric->setSampleRate(0.1);
        $this->assertEquals($metric->getData(), 'key:-1|c|@0.1');
    }
}
