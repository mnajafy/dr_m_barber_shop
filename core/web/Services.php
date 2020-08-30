<?php
namespace core\web;
use Exception;
use core\base\BaseObject;
class Services extends BaseObject {
    private $_services = [];
    public function __get($name) {
        if ($this->has($name)) {
            return $this->get($name);
        }
        return parent::__get($name);
    }
    public function __isset($name) {
        if ($this->has($name)) {
            return true;
        }
        return parent::__isset($name);
    }
    public function has($name) {
        return isset($this->_services[$name]);
    }
    public function set($name, $value) {
        $this->_services[$name] = $value;
    }
    public function get($name) {
        if (!$this->has($name)) {
            throw new Exception("Service '$name' not found!");
        }
        if (!is_object($this->_services[$name])) {
            $this->_services[$name] = BaseObject::createObject($this->_services[$name]);
        }
        return $this->_services[$name];
    }
    public function setServices($services) {
        foreach ($services as $name => $value) {
            $this->set($name, $value);
        }
    }
}