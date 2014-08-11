# Swiftmailer Filter Plugin

[![Build Status](https://travis-ci.org/OpenBuildings/swiftmailer-filter.png?branch=master)](https://travis-ci.org/OpenBuildings/swiftmailer-filter)
[![Coverage Status](https://coveralls.io/repos/OpenBuildings/swiftmailer-filter/badge.png?branch=master)](https://coveralls.io/r/OpenBuildings/swiftmailer-filter?branch=master)
[![Latest Stable Version](https://poser.pugx.org/openbuildings/swiftmailer-filter/v/stable.png)](https://packagist.org/packages/openbuildings/swiftmailer-filter)

A swiftmailer plugin that allows whitelist / blacklist to which emails to perform the sends. This is useful for example when you want to allow emails only to a certain domain in testing / staging

## Usage

```php
$mailer = Swift_Mailer::newInstance();

$mailer->registerPLugin(new FilterPlugin('example.com', array('test4@example.com', 'test5@example.com'));
```

First argument is whitelist, second is blacklist, they both allow string or an array of emails or domain names. If you assign a domain, all emails from that domain will be whitelisted / blacklisted.

There are additional getters / setters that you might use:

- ``setWhitelist($whitelist)``
- ``getWhitelist()``
- ``setBlacklist($blacklist)``
- ``getBlacklist()``

## License

Copyright (c) 2013, OpenBuildings Ltd. Developed by Ivan Kerin as part of [clippings.com](http://clippings.com)

Under BSD-3-Clause license, read LICENSE file.
