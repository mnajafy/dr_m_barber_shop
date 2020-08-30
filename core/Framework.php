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
        if (strncmp($name, '@', 1)) {
            // not an alias
            return $name;
        }
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
    public static function t($category, $message, $params = [], $language = null) {
        if (static::$app !== null) {
            return static::$app->getI18n()->translate($category, $message, $params, $language ?: static::$app->language);
        }
        $placeholders = [];
        foreach ((array) $params as $name => $value) {
            $placeholders['{' . $name . '}'] = $value;
        }
        return ($placeholders === []) ? $message : strtr($message, $placeholders);
    }
}