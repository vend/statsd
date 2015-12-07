<?php

namespace Vend\Statsd;

use PHPUnit_Framework_TestCase as BaseTest;

class ClientAwareTraitTest extends BaseTest
{
    public function testSetter()
    {
        $object = $this->getObjectForTrait(ClientAwareTrait::class);
        $object->setStatsdClient(new NullClient());
    }
}
