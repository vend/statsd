<?php

namespace Vend\Statsd;

use \PHPUnit_Framework_TestCase as BaseTest;

class ReadmeTest extends BaseTest
{
    public function testClientFactory()
    {
        $socket = $this->getMockSocket();
        $factory = $this->getMockFactory();

        $client = new Client($socket, $factory);

        $client->counter('some.key', 1);
        $client->increment('some.key');
        $client->decrement('some.key');
        $client->gauge('some.key', 10);
        $client->timer('some.key', 0.25);
        $client->set('some.key', 'something');

        $client->flush();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|FactoryInterface
     */
    protected function getMockFactory()
    {
        $metric = new Metric('foo', 1, Type::COUNTER);

        $factory = $this->getMockBuilder(Factory::class)
            ->getMock();

        $factory->expects($this->once())
            ->method('counter')
            ->will($this->returnValue($metric));

        $factory->expects($this->once())
            ->method('increment')
            ->will($this->returnValue($metric));

        $factory->expects($this->once())
            ->method('decrement')
            ->will($this->returnValue($metric));

        $factory->expects($this->once())
            ->method('gauge')
            ->will($this->returnValue($metric));

        $factory->expects($this->once())
            ->method('timer')
            ->will($this->returnValue($metric));

        $factory->expects($this->once())
            ->method('set')
            ->will($this->returnValue($metric));

        return $factory;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Socket
     */
    protected function getMockSocket()
    {
        $socket = $this->getMockBuilder(Socket::class)
            ->getMock();

        return $socket;
    }
}
