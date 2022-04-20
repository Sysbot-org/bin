<?php

declare(strict_types=1);

namespace Sysbot\Bin;

use ArithmeticError;
use BigInteger;
use Exception;
use OutOfRangeException;
use Sysbot\Bin\Common\BinaryUtils;

/**
 * Class Deserializer
 * @package Sysbot\Bin
 */
class Deserializer
{

    use BinaryUtils;

    /**
     * @var int
     */
    protected int $offset = 0;

    /**
     * @param string $bytes
     */
    public function __construct(protected string $bytes)
    {
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @param int $whence
     * @return $this
     */
    public function seek(int $offset, int $whence = SEEK_SET): self
    {
        switch ($whence) {
            case SEEK_CUR:
                $offset += $this->offset;
                break;
            case SEEK_END:
                if ($offset > 0) {
                    $offset = 0;
                }
                $offset = strlen($this->bytes) + $offset;
                break;
            default:
                break;
        }
        $this->offset = $offset;
        return $this;
    }

    /**
     * Resets the offset.
     * @return Deserializer
     */
    public function rewind(): self
    {
        return $this->seek(0);
    }

    /**
     * @param int $length
     * @param bool $bigEndian
     * @return string
     * @throws OutOfRangeException
     */
    public function getBytes(int $length, bool $bigEndian = false): string
    {
        $data = substr($this->bytes, $this->offset, $length);
        $actual = strlen($data);
        if ($actual < $length) {
            $message = sprintf('Not enough data to read: expected %d bytes, but only %d received', $length, $actual);
            throw new OutOfRangeException($message);
        }
        $this->offset += $length;
        if ($bigEndian) {
            $data = strrev($data);
        }
        return $data;
    }

    /**
     * @param bool $bigEndian
     * @return int
     * @throws OutOfRangeException
     */
    public function readLong(bool $bigEndian = false): int
    {
        $data = $this->getBytes(4, $bigEndian);
        $result = self::unpack('V', $data);
        if (null === $result) {
            throw new ArithmeticError('Cannot de-serialize long');
        }
        return $result;
    }

    /**
     * @param bool $bigEndian
     * @return int|BigInteger
     * @throws OutOfRangeException
     * @throws Exception
     * @throws ArithmeticError
     */
    public function readLongLong(bool $bigEndian = false): int|BigInteger
    {
        $data = $this->getBytes(8, $bigEndian);
        $result = BigInteger::fromBytes(IS_BIG_ENDIAN ? $data : strrev($data));
        if (IS_32_BIT) {
            return $result;
        }
        return $result->toInt();
    }

    /**
     * @return string
     * @throws OutOfRangeException
     */
    public function readString(): string
    {
        $len = ord($this->getBytes(1));
        if (254 < $len) {
            $message = sprintf('Length too big: %d (max 254)', $len);
            throw new OutOfRangeException($message);
        }
        if (254 == $len) {
            $data = $this->getBytes(3);
            $len = self::unpack('V', $data . chr(0)) + 1;
        }
        $result = $len ? $this->getBytes($len) : '';
        $this->offset += self::positiveModulo(-$len - 1, 4);
        return $result;
    }

    /**
     * @param string $format
     * @param string $data
     * @return mixed
     */
    public static function unpack(string $format, string $data): mixed
    {
        $result = @unpack($format, $data);
        if (false === $result) {
            return null;
        }
        return $result[1] ?? null;
    }

}