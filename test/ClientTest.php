<?php

namespace Vend\Statsd;

class ClientTest extends ClientInterfaceTest
{
    protected function getSut(Socket $socket, FactoryInterface $factory)
    {
        return new Client($this->socket, $this->factory);
    }

    public function testClientActuallyCallsFactory()
    {
        $metric = new Metric('foo', 1, Type::COUNTER);

        $this->factory->expects($this->once())
            ->method('counter')
            ->will($this->returnValue($metric));

        $this->factory->expects($this->once())
            ->method('increment')
            ->will($this->returnValue($metric));

        $this->factory->expects($this->once())
            ->method('decrement')
            ->will($this->returnValue($metric));

        $this->factory->expects($this->once())
            ->method('gauge')
            ->will($this->returnValue($metric));

        $this->factory->expects($this->once())
            ->method('timer')
            ->will($this->returnValue($metric));

        $this->factory->expects($this->once())
            ->method('set')
            ->will($this->returnValue($metric));

        $this->client->counter('some.key', 1);
        $this->client->increment('some.key');
        $this->client->decrement('some.key');
        $this->client->gauge('some.key', 10);
        $this->client->timer('some.key', 0.25);
        $this->client->set('some.key', 'something');

        $this->client->flush();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testBadClientFactory()
    {
        $this->client->someNonexistantMethod('some.key', 1);
    }

    /**
     * Test the protected API too
     */
    public function testFillPackets()
    {
        $data = $this->callFillPackets([]);
        $this->assertInternalType('array', $data);
        $this->assertEquals([0 => ''], $data);

        $data = $this->callFillPackets(array_fill(0, 1000, '12345'));
        $this->assertInternalType('array', $data);
        $this->assertCount(13, $data);

        foreach ($data as $packet) {
            $this->assertLessThanOrEqual(Socket::MAX_DATAGRAM_SIZE, strlen($packet));
        }
    }

    protected function callFillPackets(array $data)
    {
        $class = new \ReflectionClass($this->client);
        $method = $class->getMethod('fillPackets');
        $method->setAccessible(true);

        return $method->invokeArgs($this->client, [
            $data
        ]);
    }
}
