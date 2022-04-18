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
     * @param int|string $value
     * @return static
     * @throws Exception
     */
    public static function of(int|string $value): self
    {
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

    public function toInt(): int
    {
        return (int)$this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

}