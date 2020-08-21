<?php
namespace core\web;
use Exception;
use core\base\BaseObject;
class Services extends BaseObject {
    public $services = [];
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
        return isset($this->services[$name]);
    }
    public function set($name, $value) {
        $this->services[$name] = $value;
    }
    public function get($name) {
        if (!isset($this->services[$name])) {
            throw new Exception("Service '$name' not found!");
        }
        if (!is_object($this->services[$name])) {
            $this->services[$name] = BaseObject::createObject($this->services[$name]);
        }
        return $this->services[$name];
    }
    public function setServices($services) {
        foreach ($services as $name => $value) {
            $this->set($name, $value);
        }
    }
}