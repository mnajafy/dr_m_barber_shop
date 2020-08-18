<?php
namespace core\base;
class BaseObject {
    private $_attributes = [];
    public static function createObject($config) {
        $class = null;
        if (array_key_exists('class', $config)) {
            $class = $config['class'];
            unset($config['class']);
        }
        return new $class($config);
    }
    public function __construct($config = []) {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
        $this->init();
    }
    public function init() {
        
    }
    public function __set($name, $value) {

        $methodName = 'set' . $name;
        if (method_exists($this, $methodName)) {
            $this->$methodName($value);
            return;
        }

        $this->_attributes[$name] = $value;
    }
    public function __get($name) {

        $methodName = 'get' . $name;
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }

        return null;
    }
}