<?php

namespace Vend\Statsd\Datadog;

use Vend\Statsd\Metric as BaseMetric;

class Metric extends BaseMetric
{
    /**
     * @var array<string,string>
     */
    protected $tags = [];

    public function __construct($key, $value, $type, array $tags = [])
    {
        parent::__construct($key, $value, $type);

        $this->setTags($tags);
    }

    public function setTags(array $tags = [])
    {
        $this->tags = $tags;
    }

    public function getData()
    {
        $result = parent::getData();

        if (!empty($this->tags)) {
            $tags = [];

            array_walk($this->tags, function ($tagValue, $tagName) use (&$tags) {
                $tags[] = is_integer($tagName) ? $tagValue : $tagName . ':' . $tagValue;
            });

            $result .= '|#' . implode(',', $tags);
        }

        return $result;
    }
}
