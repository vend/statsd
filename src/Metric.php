<?php

namespace Vend\Statsd;

class Metric implements MetricInterface
{
    protected $key;
    protected $value;
    protected $type;
    protected $sampleRate = 1.0;

    public function __construct($key, $value, $type)
    {
        $this->setKey($key);
        $this->setValue($value);
        $this->setType($type);
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setSampleRate($sampleRate)
    {
        $this->sampleRate = $sampleRate;
    }

    public function getData()
    {
        $result = sprintf('%s:%s|%s', $this->key, $this->value, $this->type);

        if ($this->sampleRate < 1.0) {
            $result .= '|@' . $this->sampleRate;
        }

        return $result;
    }
}
