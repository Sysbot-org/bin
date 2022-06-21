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
        $bytes = pack('q', 1);
        $bytes = IS_BIG_ENDIAN ? $bytes : strrev($bytes);
        $int = BigInteger::fromBytes($bytes);
        $this->assertEquals(1, $int->toInt());
        $this->assertEquals($bytes, $int->toBytes());
    }

    public function testInvalidFromBytes()
    {
        $this->expectException(ArithmeticError::class);
        $this->expectExceptionMessage('Cannot de-serialize from bytes');
        BigInteger::fromBytes(pack('V', 0));
    }

    public function testCompareTo()
    {
        $int = BigInteger::of(5);
        $this->assertEquals(1, $int->compareTo(-13));
        $this->assertEquals(0, $int->compareTo(5));
        $this->assertEquals(-1, $int->compareTo(18));
    }

    public function testPlus()
    {
        $int = BigInteger::of(2);
        $this->assertEquals(7, $int->plus(5)->toInt());
        $this->assertEquals(5, $int->plus('-2')->toInt());
        $this->assertEquals(16, $int->plus(BigInteger::of(11))->toInt());
    }

    public function testMinus()
    {
        $int = BigInteger::of(2);
        $this->assertEquals(-3, $int->minus(5)->toInt());
        $this->assertEquals(-1, $int->minus('-2')->toInt());
        $this->assertEquals(-12, $int->minus(BigInteger::of(11))->toInt());
    }

    public function testMultipliedBy()
    {
        $int = BigInteger::of(2);
        $this->assertEquals(10, $int->multipliedBy(5)->toInt());
        $this->assertEquals(-20, $int->multipliedBy('-2')->toInt());
        $this->assertEquals(-220, $int->multipliedBy(BigInteger::of(11))->toInt());
    }

    public function testShiftedLeft()
    {
        $int = BigInteger::of(2);
        $this->assertEquals(2 << 2, $int->shiftedLeft(2)->toInt());
        $this->assertEquals(2 << 4, $int->shiftedLeft(4)->toInt());
        $this->assertEquals(2 << 10, $int->shiftedLeft(10)->toInt());
    }

    public function testShiftedRight()
    {
        $int = BigInteger::of(8192);
        $this->assertEquals(8192 >> 2, $int->shiftedRight(2)->toInt());
        $this->assertEquals(8192 >> 4, $int->shiftedRight(4)->toInt());
        $this->assertEquals(8192 >> 10, $int->shiftedRight(10)->toInt());
    }

}
