<?php

declare(strict_types=1);

namespace Sysbot\Tests\Bin;

use BigInteger;
use PHPUnit\Framework\TestCase;
use Sysbot\Bin\Deserializer;

/**
 * @covers \Sysbot\Bin\Deserializer
 * @uses   BigInteger
 */
class DeserializerTest extends TestCase
{

    public function testReadLongLong()
    {
        $deserializer = new Deserializer(BigInteger::of(1)->toBytes());
        $this->assertEquals(1, $deserializer->readLongLong(true));
        $deserializer = new Deserializer(pack('V', 1));
        $this->assertEquals(1, $deserializer->readLong());
    }

    public function testSeek()
    {
        $deserializer = new Deserializer(BigInteger::of(0)->toBytes());
        $deserializer->seek(3);
        $this->assertEquals(3, $deserializer->getOffset());
        $deserializer->seek(2, SEEK_CUR);
        $this->assertEquals(5, $deserializer->getOffset());
        $deserializer->seek(0, SEEK_END);
        $this->assertEquals(8, $deserializer->getOffset());
    }

    public function testRewind()
    {
        $deserializer = new Deserializer(chr(0));
        $deserializer->seek(0, SEEK_END);
        $end = $deserializer->getOffset();
        $deserializer->rewind();
        $offset = $deserializer->getOffset();
        $this->assertTrue($offset < $end and $offset === 0);
    }

    public function testUnpack()
    {
        $bytes = pack('V', 0);
        $this->assertEquals(unpack('V', $bytes)[1], Deserializer::unpack('V', $bytes));
    }

    public function testReadString()
    {
        $expected = 'Hi mom!';
        $deserializer = new Deserializer(chr(7) . 'Hi mom!');
        $this->assertEquals($expected, $deserializer->readString());
    }

    public function testReadLong()
    {
        $deserializer = new Deserializer(pack('V', 1));
        $this->assertEquals(1, $deserializer->readLong());
    }

    public function testGetBytes()
    {
        $bytes = BigInteger::of(rand(PHP_INT_MIN, PHP_INT_MAX))->toBytes();
        $deserializer = new Deserializer($bytes);
        $this->assertEquals($bytes, $deserializer->getBytes(strlen($bytes)));
    }

    public function testGetOffset()
    {
        $deserializer = new Deserializer('');
        $this->assertEquals(0, $deserializer->getOffset());
    }
}
