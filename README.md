# Sysbot-bin

[![License](http://poser.pugx.org/sysbot/bin/license)](https://packagist.org/packages/sysbot/bin)
![Required PHP Version](https://img.shields.io/badge/php-%E2%89%A58.0-brightgreen)
[![Latest Stable Version](http://poser.pugx.org/sysbot/bin/v)](https://packagist.org/packages/sysbot/bin)
[![Tests](https://github.com/Sysbot-org/bin/actions/workflows/tests.yml/badge.svg)](https://github.com/Sysbot-org/bin/actions)
[![Dependencies](https://img.shields.io/librariesio/github/Sysbot-org/bin)](https://libraries.io/github/Sysbot-org/bin)
[![Code Quality](https://img.shields.io/scrutinizer/quality/g/Sysbot-org/bin)](https://scrutinizer-ci.com/g/Sysbot-org/bin/?branch=main)

Sysbot/bin is a library used by Sysbot. It handles serialization and deserialization of binary data, providing support
for both 32-bit and 64-bit systems.

## Changelog

You can find the [changelog](CHANGELOG.md) here.

## Installation

Install the library with composer:

`$ composer require sysbot/bin --prefer-stable`

**(32-bit systems)** You must also install the required
dependency [brick/math](https://packagist.org/packages/brick/math).

`$ composer require "brick/math:^0.9"`

## Usage

Here's an example on how to use the library.

```php
<?php

require_once 'vendor/autoload.php';

use Sysbot\Bin\Serializer;
use Sysbot\Bin\Deserializer;

// Serialization

$serializer = new Serializer();

$serializer->addLong(15); // adds a 32-bit integer (little-endian)

$long = PHP_INT_MAX;

if (PHP_INT_SIZE === 4) { // 32-bit systems
    // since 32-bit systems can't handle 64-bit numbers, we must relay on the BigInteger class
    $long = BigInteger::of('9223372036854775807');
}
$serializer->addLongLong($long, true); // adds a 64-bit integer (big-endian)

$serializer->addString('Hi mom!'); // adds a string

// casting a Serializer instance to a string will return the bytes
echo bin2hex((string)$serializer); // outputs "0f0000007fffffffffffffff074869206d6f6d21"


// Deserialization

$deserializer = new Deserializer((string)$serializer);

echo $deserializer->readLong(); // reads a 32-bit integer (little-endian), outputs "15"

$long = $deserializer->readLongLong(true); // reads a 64-bit integer (big-endian)

if (PHP_INT_SIZE === 4) {
    $long = (string)$long; // on 32-bit systems, an instance of the BigInteger class will be returned: to get the number, we must cast to string
}

echo $long; // outputs "9223372036854775807"

echo $deserializer->readString(); // reads a string, outputs "Hi mom!"
```