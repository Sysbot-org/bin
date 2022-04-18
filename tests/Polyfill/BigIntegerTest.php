<?php

declare(strict_types=1);

namespace Sysbot\Tests\Bin\Polyfill;

use ArithmeticError;
use PHPUnit\Framework\TestCase;
use Sysbot\Bin\Polyfill\BigInteger;

class BigIntegerTest extends TestCase
{

    protected function setUp(): void
    {
        if (IS_32_BIT) {
            $this->markTestSkipped('Polyfill not supported on 32-bit systems.');
        }
    }

    public function testToBytes()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $int = BigInteger::of(0);
        $bytes = pack('q', 0);
        if (IS_BIG_ENDIAN) {
            $bytes = strrev($bytes);
        }
        $this->assertEquals($bytes, $int->toBytes());
    }

    public function test__toString()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $int = BigInteger::of(0);
        $this->assertEquals('0', (string)$int);
    }

    public function testOf()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertInstanceOf(BigInteger::class, BigInteger::of(0));
    }

    public function testToInt()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $int = BigInteger::of(0);
        $this->assertEquals(0, $int->toInt());
    }

    public function testFromBytes()
    {
        $bytes = pack('q', 0);
        $bytes = IS_BIG_ENDIAN ? $bytes : strrev($bytes);
        $int = BigInteger::fromBytes($bytes);
        $this->assertEquals(0, $int->toInt());
        $this->assertEquals($bytes, $int->toBytes());
    }

    public function testInvalidFromBytes()
    {
        $this->expectException(ArithmeticError::class);
        $this->expectExceptionMessage('Cannot de-serialize from bytes');
        BigInteger::fromBytes(pack('V', 0));
    }
}
