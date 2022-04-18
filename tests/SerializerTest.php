<?php

declare(strict_types=1);

namespace Sysbot\Tests\Bin;

use BigInteger;
use PHPUnit\Framework\TestCase;
use Sysbot\Bin\Serializer;

class SerializerTest extends TestCase
{

    public function testAddString()
    {
        $serializer = new Serializer();
        $serializer->addString('Hi mom!');
        $this->assertEquals(chr(7) . 'Hi mom!', (string)$serializer);
    }

    public function testPack()
    {
        $this->assertEquals(pack('V', 0), Serializer::pack('V', 0));
        $this->assertEquals(pack('N', 0), Serializer::pack('V', 0, true));
    }

    public function testAddLongLong()
    {
        $bytes = BigInteger::of(1)->toBytes();
        $serializer = new Serializer();
        $serializer->addLongLong(1);
        $this->assertEquals(strrev($bytes), (string)$serializer);
    }

    public function testAddLongLongBigInt()
    {
        $int = BigInteger::of(1);
        $serializer = new Serializer();
        $serializer->addLongLong($int);
        $this->assertEquals(strrev($int->toBytes()), (string)$serializer);
    }

    public function test__toString()
    {
        $expected = rand(PHP_INT_MIN, PHP_INT_MAX);
        $serializer = new Serializer();
        $serializer->addLongLong($expected);
        $this->assertEquals(strrev(BigInteger::of($expected)->toBytes()), (string)$serializer);
    }

    public function testAddLong()
    {
        $serializer = new Serializer();
        $serializer->addLong(1);
        $this->assertEquals(pack('V', 1), (string)$serializer);
    }
}
