<?php

namespace Vend\Statsd;

class Socket
{
    /**
     * Being fairly conservative here:
     *
     *  Implementations of the IP protocol are not required to be capable of handling arbitrarily large packets. In
     *  theory, the maximum possible IP packet size is 65,535 octets, but the standard only requires that implementations
     *  support at least 576 octets.
     *
     * via http://stackoverflow.com/a/3712822/10831
     *
     * Over this limit, we'll just fragment at the application layer, and send more than one packet.
     */
    const MAX_DATAGRAM_SIZE = 500;

    protected $host;
    protected $port;
    protected $socket = null;

    /**
     * Socket constructor
     *
     * @param string $host
     * @param int    $port
     */
    public function __construct($host = '127.0.0.1', $port = 8125)
    {
        $this->host = $host;
        $this->port = $port;
        $this->socket = null;
    }

    /**
     * @return void
     */
    public function open()
    {
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP) ?: null;
    }

    /**
     * @param string $data
     * @return int|null Number of bytes written
     */
    public function write($data)
    {
        if (!$this->socket) {
            $this->open();
        }

        if (!$this->socket) {
            return null;
        }

        return socket_sendto($this->socket, $data, strlen($data), 0, $this->host, $this->port);
    }

    /**
     * Closes the socket
     */
    public function close()
    {
        if ($this->socket && is_resource($this->socket)) {
            socket_close($this->socket);
        }

        $this->socket = null;
    }
}
