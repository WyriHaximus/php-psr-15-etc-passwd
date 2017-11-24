# [PSR-15](http://www.php-fig.org/psr/psr-15/) middleware that pretends to expose /etc/passwd and /etc/shadow

[![Linux Build Status](https://travis-ci.org/WyriHaximus/php-psr-15-etc-passwd.png)](https://travis-ci.org/WyriHaximus/php-psr-15-etc-passwd)
[![Latest Stable Version](https://poser.pugx.org/WyriHaximus/psr-15-etc-passwd/v/stable.png)](https://packagist.org/packages/WyriHaximus/psr-15-etc-passwd)
[![Total Downloads](https://poser.pugx.org/WyriHaximus/psr-15-etc-passwd/downloads.png)](https://packagist.org/packages/WyriHaximus/psr-15-etc-passwd/stats)
[![Code Coverage](https://scrutinizer-ci.com/g/WyriHaximus/php-psr-15-etc-passwd/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/WyriHaximus/php-psr-15-etc-passwd/?branch=master)
[![License](https://poser.pugx.org/WyriHaximus/psr-15-etc-passwd/license.png)](https://packagist.org/packages/wyrihaximus/psr-15-etc-passwd)
[![PHP 7 ready](http://php7ready.timesplinter.ch/WyriHaximus/php-psr-15-etc-passwd/badge.svg)](https://travis-ci.org/WyriHaximus/php-psr-15-etc-passwd)

### Installation ###

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require wyrihaximus/psr-15-etc-passwd 
```

## Usage ##

```php
$middleware = new EtcPasswdMiddleware([
    'username' => 'password', // Do not use real passwords here for the sake of the security of your system
    'another_username' => 'another_password',
]);
```

## Inspired by ##

[`Reddit`](https://www.reddit.com/etc/passwd)

## Contributing ##

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License ##

Copyright 2017 [Cees-Jan Kiewiet](http://wyrihaximus.net/)

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
