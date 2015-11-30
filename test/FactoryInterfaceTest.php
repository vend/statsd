<?php

namespace Vend\Statsd;

use PHPUnit_Framework_TestCase as BaseTest;

abstract class FactoryInterfaceTest extends BaseTest
{
    protected $factory;

    /**
     * @return FactoryInterface
     */
    abstract protected function getFactory();

    public function setUp()
    {
        $this->factory = $this->getFactory();
    }

    public function testTimer()
    {
        $metric = $this->factory->timer('key', 1.23456);
        $data = $metric->getData();

        $this->assertInstanceOf(MetricInterface::class, $metric);
        $this->assertContains('1.23456', $data, 'Value is present in message data');
        $this->assertStringStartsWith('key', $data);
        $this->assertStringEndsWith('ms', $data);
    }

    public function testGauge()
    {
        $metric = $this->factory->gauge('key', 123456);
        $data = $metric->getData();

        $this->assertInstanceOf(MetricInterface::class, $metric);
        $this->assertContains('123456', $data, 'Value is present in message data');
        $this->assertStringStartsWith('key', $data);
        $this->assertStringEndsWith('g', $data);
    }

    public function testDecrement()
    {
        $metric = $this->factory->decrement('key');
        $data = $metric->getData();

        $this->assertInstanceOf(MetricInterface::class, $metric);
        $this->assertStringStartsWith('key', $data);
        $this->assertStringEndsWith('c', $data);
    }
}
