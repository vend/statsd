<?php

namespace Vend\Statsd;

use PHPUnit_Framework_TestCase as BaseTest;

abstract class ClientInterfaceTest extends BaseTest
{
    protected $socket;
    protected $factory;
    protected $client;

    /**
     * @param Socket           $socket
     * @param FactoryInterface $factory
     * @return ClientInterface
     */
    abstract protected function getSut(Socket $socket, FactoryInterface $factory);

    public function setUp()
    {
        $this->socket = $this->getMockSocket();
        $this->factory = $this->getMockFactory();
        $this->client = $this->getSut($this->socket, $this->factory);
    }

    public function tearDown()
    {
        $this->socket = null;
        $this->factory = null;
        $this->client = null;
    }

    public function testClientFactoryMethods()
    {
        $metric = new Metric('foo', 1, Type::COUNTER);

        $this->factory->expects($this->any())
            ->method('counter')
            ->will($this->returnValue($metric));

        $this->factory->expects($this->any())
            ->method('increment')
            ->will($this->returnValue($metric));

        $this->factory->expects($this->any())
            ->method('decrement')
            ->will($this->returnValue($metric));

        $this->factory->expects($this->any())
            ->method('gauge')
            ->will($this->returnValue($metric));

        $this->factory->expects($this->any())
            ->method('timer')
            ->will($this->returnValue($metric));

        $this->factory->expects($this->any())
            ->method('set')
            ->will($this->returnValue($metric));

        $this->client->counter('some.key', 1);
        $this->client->increment('some.key');
        $this->client->decrement('some.key');
        $this->client->gauge('some.key', 10);
        $this->client->timer('some.key', 0.25);
        $this->client->set('some.key', 'something');
    }

    public function testFlush()
    {
        $this->client->flush();
    }

    public function testAdd()
    {
        $metric = $this->getMockBuilder(Metric::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->client->add($metric);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|FactoryInterface
     */
    protected function getMockFactory()
    {
        $factory = $this->getMockBuilder(Factory::class)
            ->getMock();

        return $factory;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Socket
     */
    protected function getMockSocket()
    {
        $socket = $this->getMockBuilder(Socket::class)
            ->setMethods(null)
            ->getMock();

        return $socket;
    }
}
