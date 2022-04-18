<?php

declare(strict_types=1);

namespace Sysbot\Bin\Common;

trait BinaryUtils
{

    /**
     * @param string $string
     * @return string
     */
    public static function rleEncode(string $string): string
    {
        $new = '';
        $count = 0;
        $null = chr(0);
        $chars = str_split($string);
        foreach ($chars as $char) {
            if ($char === $null) {
                $count++;
                continue;
            }
            if ($count > 0) {
                $new .= $null . chr($count);
                $count = 0;
            }
            $new .= $char;
        }
        return $new;
    }

    /**
     * @param string $string
     * @return string
     */
    public static function rleDecode(string $string): string
    {
        $new = '';
        $last = '';
        $null = chr(0);
        $chars = str_split($string);
        foreach ($chars as $char) {
            if ($last === $null) {
                $new .= str_repeat($last, ord($char));
                $last = '';
                continue;
            }
            $new .= $last;
            $last = $char;
        }
        return $new . $last;
    }

    /**
     * @param string $string
     * @return string
     */
    public static function base64UrlDecode(string $string): string
    {
        return base64_decode(str_pad(strtr($string, '-_', '+/'), strlen($string) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * @param string $string
     * @return string
     */
    public static function base64UrlEncode(string $string): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($string));
    }

    /**
     * @param int $a
     * @param int $b
     * @return int
     */
    public static function positiveModulo(int $a, int $b): int
    {
        $remainder = $a % $b;
        if ($remainder < 0) {
            $remainder += abs($b);
        }
        return $remainder;
    }

}