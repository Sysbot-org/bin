<?php

declare(strict_types=1);

namespace Sysbot\Tests\Bin\Common;

use PHPUnit\Framework\TestCase;
use Sysbot\Bin\Common\BinaryUtils;

class BinaryUtilsTest extends TestCase
{

    public function testRleEncode()
    {
        $expected = 'A' . chr(0) . chr(16) . 'B';
        $this->assertEquals($expected, BinaryUtils::rleEncode('A' . str_repeat(chr(0), 16) . 'B'));
    }

    public function testRleDecode()
    {
        $expected = 'A' . str_repeat(chr(0), 16) . 'B';
        $this->assertEquals($expected, BinaryUtils::rleDecode('A' . chr(0) . chr(16) . 'B'));
    }

    public function testBase64UrlEncode()
    {
        $this->assertEquals('SGkgbW9tIQ', BinaryUtils::base64UrlEncode('Hi mom!'));
    }

    public function testBase64UrlDecode()
    {
        $this->assertEquals('Hi mom!', BinaryUtils::base64UrlDecode('SGkgbW9tIQ'));
    }

    public function testPositiveModulo()
    {
        $this->assertEquals(3, BinaryUtils::positiveModulo(-7, -5));
    }

}
