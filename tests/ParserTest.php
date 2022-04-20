<?php

declare(strict_types=1);

namespace Sysbot\Tests\Bin;

use PHPUnit\Framework\TestCase;
use Sysbot\Bin\Constants\Formats;
use Sysbot\Bin\Parser;
use Sysbot\Bin\Polyfill\BigInteger;

class ParserTest extends TestCase
{

    public function testEncode()
    {
        $expected = pack('VN', 1, 1) . chr(7) . 'Hi mom!' . BigInteger::of(1)->toBytes();
        $actual = Parser::encode(
            [
                ['format' => Formats::LONG, 'value' => 1],
                ['format' => Formats::LONG, 'value' => 1, 'big_endian' => true],
                ['format' => Formats::STRING, 'value' => 'Hi mom!'],
                ['format' => '', 'value' => 'This will not be part of the result.'],
                ['format' => Formats::LONG_LONG, 'value' => 1, 'big_endian' => true]
            ]
        );
        $this->assertEquals($expected, $actual);
    }

    public function testDecode()
    {
        $expected = [1, 1, 'Hi mom!', 1];
        $bytes = pack('VN', 1, 1) . chr(7) . 'Hi mom!' . BigInteger::of(1)->toBytes();
        $actual = Parser::decode($bytes, [
            ['format' => Formats::LONG],
            ['format' => Formats::LONG, 'big_endian' => true],
            ['format' => ''],
            ['format' => Formats::STRING],
            ['format' => Formats::LONG_LONG, 'big_endian' => true]
        ]);
        $this->assertEquals($expected, $actual);
    }
}
