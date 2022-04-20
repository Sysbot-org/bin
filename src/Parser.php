<?php

declare(strict_types=1);

namespace Sysbot\Bin;

use Exception;
use Sysbot\Bin\Constants\Formats;

/**
 * Class Parser
 * @package Sysbot\Bin
 */
class Parser
{

    /**
     * @param string $bytes
     * @param array $data
     * @return array<array{format: string, big_endian?: bool}>
     * @throws Exception
     */
    public static function decode(string $bytes, array $data): array
    {
        $deserializer = new Deserializer($bytes);
        $result = [];
        $index = 0;
        foreach ($data as $value) {
            $format = $value['format'] ?? null;
            if (empty($format)) {
                continue;
            }
            $bigEndian = $value['big_endian'] ?? false;
            $result[$index] = match ($format) {
                Formats::LONG => $deserializer->readLong($bigEndian),
                Formats::LONG_LONG => $deserializer->readLongLong($bigEndian),
                Formats::STRING => $deserializer->readString()
            };
            $index++;
        }
        return $result;
    }

    /**
     * @param array<array{format: string, value: mixed, big_endian?: bool}> $data
     * @return string
     * @throws Exception
     */
    public static function encode(array $data): string
    {
        $serializer = new Serializer();
        foreach ($data as $value) {
            $format = $value['format'] ?? null;
            $content = $value['value'] ?? null;
            if (empty($format)) {
                continue;
            }
            $bigEndian = $value['big_endian'] ?? false;
            match ($format) {
                Formats::LONG => $serializer->addLong($content ?? 0, $bigEndian),
                Formats::LONG_LONG => $serializer->addLongLong($content ?? 0, $bigEndian),
                Formats::STRING => $serializer->addString((string)$content)
            };
        }
        return (string)$serializer;
    }

}