# Swiftmailer Filter Plugin

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