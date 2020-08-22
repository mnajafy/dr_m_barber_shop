<?php
namespace core\web;
use core\base\BaseObject;
use core\helpers\ArrayHelper;
/**
 * @property array $params
 */
class Cookie extends BaseObject {
    private $_params = [
        'expires'  => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => false,
        'samesite' => 'Strict',
        'httponly' => true
    ];
    public function setParams($params) {
        $this->_params = ArrayHelper::merge($this->_params, array_change_key_case($params));
    }
    public function getParams() {
        return $this->_params;
    }
    public function set($key, $value, $params = []) {
        $options = ArrayHelper::merge($this->_params, array_change_key_case($params));
        if (PHP_VERSION_ID >= 70300) {
            setcookie($key, $value, $options);
        }
        else {
            setcookie($key, $value, $options['expires'], $options['path'], $options['domain'], $options['secure'], $options['httponly']);
        }
    }
    public function get($key, $defaultValue = null) {
        return $this->has($key) ? $_COOKIE[$key] : $defaultValue;
    }
    public function has($key) {
        return isset($_COOKIE[$key]);
    }
    public function remove($key, $params = []) {
        if ($this->has($key)) {
            $value              = $_COOKIE[$key];
            $options            = ArrayHelper::merge($this->_params, array_change_key_case($params));
            $options['expires'] = time() - 3600;
            if (PHP_VERSION_ID >= 70300) {
                setcookie($key, $value, $options);
            }
            else {
                setcookie($key, $value, $options['expires'], $options['path'], $options['domain'], $options['secure'], $options['httponly']);
            }
            unset($_COOKIE[$key]);
            return $value;
        }
        return null;
    }
    public function removeAll($params = []) {
        $options            = ArrayHelper::merge($this->_params, array_change_key_case($params));
        $options['expires'] = time() - 3600;
        foreach (array_keys($_COOKIE) as $key) {
            if (PHP_VERSION_ID >= 70300) {
                setcookie($key, null, $options);
            }
            else {
                setcookie($key, $value, $options['expires'], $options['path'], $options['domain'], $options['secure'], $options['httponly']);
            }
            unset($_COOKIE[$key]);
        }
    }
}