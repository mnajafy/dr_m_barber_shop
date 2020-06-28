<?php
/**
 * Framework
 */
class Framework {
    /**
     * @var \Core\App
     */
    public static $app;
    private static $_attributes = [];
    public static function set($name, $value) {
        static::$_attributes[$name] = $value;
    }
    public static function get($name) {
        if (array_key_exists($name, static::$_attributes)) {
            return static::$_attributes[$name];
        }
        return null;
    }
}