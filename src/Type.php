<?php

namespace Vend\Statsd;

abstract class Type
{
    /**
     * Counter type
     */
    const COUNTER = 'c';

    /**
     * Timer type
     */
    const TIMER = 'ms';

    /**
     * Gauge type
     */
    const GAUGE = 'g';

    /**
     * Set type
     */
    const SET = 's';

    /**
     * Histogram type
     */
    const HISTOGRAM = 'h';
}
