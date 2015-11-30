# vend/statsd
## Statsd Client for PHP

[![Build Status](https://secure.travis-ci.org/vend/statsd-php-client.png)](http://travis-ci.org/vend/statsd-php-client)

### Features

* Correctly splits large numbers of metrics across multiple datagrams
* Can substitute out the underlying format of the metrics being sent, to support things like Datadog's tags and histograms
* Supports sample rates, but leaves skipping the send to the caller (use the factory and socket directly)
* Doesn't block during metric sending, and only supports UDP (opinionated, but that's all you should need)
