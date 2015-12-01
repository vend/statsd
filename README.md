# vend/statsd
## Simple extensible StatsD client for PHP 5.5+

[![Build Status](https://secure.travis-ci.org/vend/statsd.png)](http://travis-ci.org/vend/statsd)
[![Code Coverage](https://scrutinizer-ci.com/g/vend/statsd/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/vend/statsd/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vend/statsd/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vend/statsd/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/vend/statsd/version)](https://packagist.org/packages/vend/statsd)
[![Latest Unstable Version](https://poser.pugx.org/vend/statsd/v/unstable)](//packagist.org/packages/vend/statsd)

### Features

* Correctly splits large numbers of metrics across multiple datagrams
* Can substitute out the underlying format of the metrics being sent, to support things like Datadog's tags and histograms
* Supports sample rates, but leaves skipping the send to the caller (use the factory and socket directly)
* Doesn't block during metric sending, and only supports UDP (opinionated, but that's all you should need)

### Usage

This library uses dependency injection throughout. To instantiate a Client, you'll need a Socket and a Factory:

```php
use Vend\Statsd\Client;
use Vend\Statsd\Socket;
use Vend\Statsd\Factory;

$client = new Client(
    new Socket(),
    new Factory()
);
```

Once you have a client, you can use the familiar statsd methods to enqueue metrics on the client. The client doesn't send
them until `->flush()` is called.

```php
$client->increment('some.metric_key'); // incremented by 1
$client->decrement('some.metric_key'); // decremented by 1
$client->counter('some.counter', 3);   // incremented by 3
$client->gauge('some.gauge', 10);
$client->timer('some.timer', 0.25);
$client->set('some.set', 'some_value');

$client->flush(); // actually sends the metrics
```

These methods return the `MetricInterface` produced by the factory (so you can then attach extra information).

You can also use the factory and socket to directly and immediately send metrics. The `->getData()` method on `MetricInterface`
provides the serialized string that should be sent to the statsd server.

```php
$socket = new Socket('127.0.0.1', 8125);
$factory = new Factory();

$socket->open();
$socket->write($factory->increment('some.key')->getData());
$socket->close();
```


#### Extending

For an example of how to extend the library, see the `Vend\Statsd\Datadog` namespace. A different `Factory` is used
 by passing it to the Client. The Datadog-specific factory will allow the metrics to carry tag information.
