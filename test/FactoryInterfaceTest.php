<?php

namespace Vend\Statsd;

use PHPUnit_Framework_TestCase as BaseTest;
use Psr\Log\NullLogger;

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

    public function tearDown()
    {
        $this->factory = null;
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

    public function testIncrement()
    {
        $metric = $this->factory->increment('key');
        $data = $metric->getData();

        $this->assertInstanceOf(MetricInterface::class, $metric);
        $this->assertStringStartsWith('key', $data);
        $this->assertStringEndsWith('c', $data);
    }

    public function testDecrement()
    {
        $metric = $this->factory->decrement('key');
        $data = $metric->getData();

        $this->assertInstanceOf(MetricInterface::class, $metric);
        $this->assertStringStartsWith('key', $data);
        $this->assertStringEndsWith('c', $data);
    }

    public function testSet()
    {
        $metric = $this->factory->set('key', 'some_value');
        $data = $metric->getData();

        $this->assertInstanceOf(MetricInterface::class, $metric);
        $this->assertStringStartsWith('key', $data);
        $this->assertStringEndsWith('s', $data);
    }

    public function testGaugeDelta()
    {
        $metric = $this->factory->set('key', '-200');
        $data = $metric->getData();

        $this->assertInstanceOf(MetricInterface::class, $metric);
        $this->assertStringStartsWith('key', $data);
        $this->assertStringEndsWith('s', $data);
    }

    public function testInvalidTimer()
    {
        $metric = $this->factory->timer('key', 'some_invalid_value');
        $this->assertNull($metric);
    }

    public function testInvalidGauge()
    {
        $metric = $this->factory->gauge('key', 'some_invalid_value');
        $this->assertNull($metric);
    }

    public function testLoggerAware()
    {
        $logger = $this->getMockBuilder(NullLogger::class)
            ->getMock();

        $logger->expects($this->once())
            ->method('error');

        $this->factory->setLogger($logger);

        $this->factory->counter('key', 'some_invalid_value');
    }
}
