<?php

use Composer\InstalledVersions;

const IS_32_BIT = PHP_INT_SIZE === 4;
define('IS_BIG_ENDIAN', pack('S', 1) === pack('n', 1));

if (IS_32_BIT and !InstalledVersions::isInstalled('brick/math')) {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw new Exception(
        'Missing required dependency for 32-bit systems: brick/math. Please run \'composer require "brick/math:^0.10"\' on your machine.'
    );
}

if (class_exists('Brick\Math\BigInteger')) {
    class_alias('Brick\Math\BigInteger', 'BigInteger');
} else {
    class_alias(\Sysbot\Bin\Polyfill\BigInteger::class, 'BigInteger');
}