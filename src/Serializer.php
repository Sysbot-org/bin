<?php

declare(strict_types=1);

namespace Sysbot\Bin;

use BigInteger;
use Exception;
use Sysbot\Bin\Common\BinaryUtils;

/**
 * Class Serializer
 * @package Sysbot\Bin
 */
class Serializer
{

    use BinaryUtils;

    /**
     * @var string
     */
    protected string $bytes = '';

    /**
     * @param string $format
     * @param mixed|null $value
     * @param bool $bigEndian
     * @return string|null
     */
    public static function pack(string $format, mixed $value = null, bool $bigEndian = false): ?string
    {
        /** @var string|false $result */
        $result = pack($format, $value);
        if (false === $result) {
            return null;
        }
        if ($bigEndian) {
            $result = strrev($result);
        }
        return $result;
    }

    /**
     * @param int $value
     * @param bool $bigEndian
     * @return $this
     */
    public function addLong(int $value, bool $bigEndian = false): self
    {
        $data = self::pack('V', $value);
        if ($bigEndian) {
            $data = strrev($data);
        }
        $this->bytes .= $data;
        return $this;
    }

    /**
     * @param int|BigInteger $value
     * @param bool $bigEndian
     * @return $this
     * @throws Exception
     */
    public function addLongLong(int|BigInteger $value, bool $bigEndian = false): self
    {
        if (IS_32_BIT and is_int($value)) {
            $value = BigInteger::of($value);
        }
        if ($value instanceof BigInteger) {
            $data = $value->toBytes();
            if (!$bigEndian) {
                $data = strrev($data);
            }
            $this->bytes .= $data;
            return $this;
        }
        $data = self::pack('q', $value);
        if (IS_BIG_ENDIAN) {
            $data = strrev($data);
        }
        if ($bigEndian) {
            $data = strrev($data);
        }
        $this->bytes .= $data;
        return $this;
    }

    /**
     * @param string $str
     * @return $this
     */
    public function addString(string $str): self
    {
        $len = strlen($str);
        $data = chr($len);
        if (253 < $len) {
            $data = chr(254) . substr(self::pack('V', $len), 0, 3);
            $len++;
        }
        $data .= $str . pack('@' . self::positiveModulo(-$len - 1, 4));
        $this->bytes .= $data;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->bytes;
    }

}