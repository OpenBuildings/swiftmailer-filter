# Swiftmailer Filter Plugin

[![Build Status](https://travis-ci.org/OpenBuildings/swiftmailer-filter.svg?branch=master)](https://travis-ci.org/OpenBuildings/swiftmailer-filter)
[![Code Coverage](https://scrutinizer-ci.com/g/OpenBuildings/swiftmailer-filter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/OpenBuildings/swiftmailer-filter/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OpenBuildings/swiftmailer-filter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/OpenBuildings/swiftmailer-filter/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/openbuildings/swiftmailer-filter/v/stable.png)](https://packagist.org/packages/openbuildings/swiftmailer-filter)

A swiftmailer plugin that allows whitelist / blacklist to which emails to perform the sends. This is useful for example when you want to allow emails only to a certain domain in testing / staging

## Usage

```php
$mailer = Swift_Mailer::newInstance();

$mailer->registerPLugin(new FilterPlugin([
    new WhiteListFilter(['example.com']),
    new BlacklistFilter(['test4@example.com, test5@example.com'])
]));
```

First argument is whitelist filter, second is blacklist filter, they both allow array of emails or domain names. If you assign a domain, all emails from that domain will be whitelisted / blacklisted.

## License

Copyright (c) 2015, Clippings Ltd. Developed by Ivan Kerin as part of [clippings.com](http://clippings.com)

Under BSD-3-Clause license, read LICENSE file.
