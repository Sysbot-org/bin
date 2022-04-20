<?php

declare(strict_types=1);

namespace Sysbot\Bin\Polyfill;

use ArithmeticError;
use Exception;
use Sysbot\Bin\Deserializer;
use Sysbot\Bin\Serializer;

/**
 * Polyfill class for 64-bit systems.
 *
 * Class BigInteger
 * @package Sysbot\Bin\Common
 */
class BigInteger
{

    /**
     * @throws Exception
     */
    protected function __construct(protected string $value)
    {
        if (IS_32_BIT) {
            throw new Exception('This class cannot be used on 32-bit systems');
        }
    }

    /**
     * @param BigInteger|int|string $value
     * @return static
     * @throws Exception
     */
    public static function of(BigInteger|int|string $value): self
    {
        if ($value instanceof BigInteger) {
            return $value;
        }
        return new self((string)$value);
    }

    /**
     * @param string $value
     * @return static
     * @throws ArithmeticError
     * @throws Exception
     */
    public static function fromBytes(string $value): self
    {
        $data = Deserializer::unpack('q', IS_BIG_ENDIAN ? $value : strrev($value));
        if (null === $data) {
            throw new ArithmeticError('Cannot de-serialize from bytes');
        }
        return self::of($data);
    }

    /**
     * @return string
     */
    public function toBytes(): string
    {
        $value = (int)$this->value;
        $bytes = Serializer::pack('q', $value) ?? '';
        if (!IS_BIG_ENDIAN) {
            $bytes = strrev($bytes);
        }
        return $bytes;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return (int)$this->value;
    }

    /**
     * @param int|string|BigInteger $number
     * @return int
     * @throws Exception
     */
    public function compareTo(int|string|BigInteger $number): int
    {
        $number = BigInteger::of($number);
        return (int)$this->value <=> $number->toInt();
    }

    /**
     * @param int|string|BigInteger $number
     * @return $this
     * @throws Exception
     */
    public function plus(int|string|BigInteger $number): BigInteger
    {
        $number = BigInteger::of($number);
        $this->value = (string)($this->toInt() + $number->toInt());
        return $this;
    }

    /**
     * @param int|string|BigInteger $number
     * @return $this
     * @throws Exception
     */
    public function minus(int|string|BigInteger $number): BigInteger
    {
        if ($number instanceof BigInteger) {
            $number = $number->toInt();
        }
        $number = (int)$number;
        return $this->plus(-$number);
    }

    /**
     * @param int|string|BigInteger $number
     * @return $this
     * @throws Exception
     */
    public function multipliedBy(int|string|BigInteger $number): BigInteger
    {
        $number = BigInteger::of($number);
        $this->value = (string)($this->toInt() * $number->toInt());
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

}