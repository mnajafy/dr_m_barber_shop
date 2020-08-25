<?php
namespace core\helpers;
class ArrayHelper {
    public static function merge($a, $b) {
        $args = func_get_args();
        $res  = array_shift($args);
        while (!empty($args)) {
            foreach (array_shift($args) as $k => $v) {
                if (is_int($k)) {
                    if (array_key_exists($k, $res)) {
                        $res[] = $v;
                    }
                    else {
                        $res[$k] = $v;
                    }
                }
                elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = static::merge($res[$k], $v);
                }
                else {
                    $res[$k] = $v;
                }
            }
        }
        return $res;
    }
    public static function remove(&$array, $key, $default = null) {
        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            $value = $array[$key];
            unset($array[$key]);
            return $value;
        }
        return $default;
    }
    public static function isAssociative($array, $allStrings = true) {
        if (!is_array($array) || empty($array)) {
            return false;
        }
        if ($allStrings) {
            foreach ($array as $key => $value) {
                if (!is_string($key)) {
                    return false;
                }
            }
            return true;
        }
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                return true;
            }
        }
        return false;
    }
    public static function getValue($array, $key, $default = null) {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }
        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }
        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            return $array[$key];
        }
        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key   = substr($key, $pos + 1);
        }
        if (is_object($array)) {
            return $array->$key;
        }
        elseif (is_array($array)) {
            return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
        }
        return $default;
    }
}