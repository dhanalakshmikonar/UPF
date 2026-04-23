<?php

namespace App\Support;

class ExcelValueFormatter
{
    public static function identifier(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $text = trim((string) $value);

        if ($text === '') {
            return null;
        }

        $text = trim($text, " \t\n\r\0\x0B.");

        if (preg_match('/^[+-]?\d+(?:\.\d+)?[eE][+-]?\d+$/', $text) === 1) {
            return number_format((float) $text, 0, '', '');
        }

        if (preg_match('/^\d+\.0+$/', $text) === 1) {
            return strstr($text, '.', true) ?: $text;
        }

        return $text;
    }
}
