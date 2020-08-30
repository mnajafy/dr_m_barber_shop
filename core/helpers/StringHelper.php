<?php
namespace core\helpers;
class StringHelper {
    public static function byteLength($string) {
        return mb_strlen($string, '8bit');
    }
    public static function byteSubstr($string, $start, $length = null) {
        return mb_substr($string, $start, $length === null ? mb_strlen($string, '8bit') : $length, '8bit');
    }
    public static function normalizeNumber($value) {
        $value            = (string) $value;
        $localeInfo       = localeconv();
        $decimalSeparator = isset($localeInfo['decimal_point']) ? $localeInfo['decimal_point'] : null;
        if ($decimalSeparator !== null && $decimalSeparator !== '.') {
            $value = str_replace($decimalSeparator, '.', $value);
        }
        return $value;
    }
    public static function base64UrlEncode($input) {
        return strtr(base64_encode($input), '+/', '-_');
    }
    public static function base64UrlDecode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}