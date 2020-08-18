<?php
/**
 * Framework
 */
class Framework {
    /**
     * @var \core\web\Application
     */
    public static $app;
    private static $_aliases = [];
    public static function setAlias($name, $value) {
        static::$_aliases[$name] = $value;
    }
    public static function getAlias($name) {
        $key   = $name;
        $value = '';
        if (strpos($name, '/') !== false) {
            $items = explode('/', $name, 2);
            $key   = $items[0];
            $value = $items[1];
        }
        if (array_key_exists($key, static::$_aliases)) {
            $alias = static::$_aliases[$key] . ($value ? '/' . $value : '');
            return rtrim($alias, '/');
        }
        return null;
    }
}