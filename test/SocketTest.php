<?php

namespace Vend\Statsd;

use \PHPUnit_Framework_TestCase as BaseTest;

class SocketTest extends BaseTest
{
    public function testSocketWithoutOpen()
    {
        $socket = new Socket();
        $socket->write('some data');
        $socket->close();
    }
}
