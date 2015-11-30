<?php

namespace Vend\Statsd;

/**
 * Metric interface
 */
interface MetricInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @param string $type
     */
    public function __construct($key, $value, $type);

    /**
     * @param string $key
     */
    public function setKey($key);

    /**
     * @param mixed $value
     */
    public function setValue($value);

    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @param float $sampleRate
     */
    public function setSampleRate($sampleRate);

    /**
     * Returns the string to use when sending the metric over the wire
     *
     * @return string
     */
    public function getData();
}
