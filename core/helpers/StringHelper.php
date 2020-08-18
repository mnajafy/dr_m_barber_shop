<?php
namespace core\helpers;
class StringHelper {
    public static function normalizeNumber($value) {
        $value = (string) $value;
        $localeInfo       = localeconv();
        $decimalSeparator = isset($localeInfo['decimal_point']) ? $localeInfo['decimal_point'] : null;
        if ($decimalSeparator !== null && $decimalSeparator !== '.') {
            $value = str_replace($decimalSeparator, '.', $value);
        }
        return $value;
    }
}