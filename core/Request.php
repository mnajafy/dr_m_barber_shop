<?php
namespace Core;
use Framework;
/**
 * Request
 * 
 * @property-read string $method
 * @property-read bool $isGet
 * @property-read bool $isPost
 */
class Request extends BaseObject {
    private $_get;
    private $_post;
    private $_files;
    private $_request_method;
    private $_script_name;
    public function init() {
        $this->_get   = $_GET;
        $this->_post  = $_POST;
        $this->_files = $_FILES;
        $this->_request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $this->_script_name = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
        Framework::setAlias('@web', str_replace('/index.php', '', $this->_script_name));
    }
    public function merge($values) {
        $this->_get = array_merge($values, $this->_get);
        return $this->_get;
    }
    public function set($name, $value = null) {
        $this->_get[$name] = $value;
    }
    public function get($name = null, $defaultValue = null) {
        if ($name === null) {
            return $this->_get;
        }
        if (isset($this->_get[$name])) {
            return $this->_get[$name];
        }
        return $defaultValue;
    }
    public function post($name = null, $defaultValue = null) {
        if ($name === null) {
            return $this->_post;
        }
        if (isset($this->_post[$name])) {
            return $this->_post[$name];
        }
        return $defaultValue;
    }
    public function files($name = null, $defaultValue = null) {
        if ($name === null) {
            return $this->_files;
        }
        if (isset($this->_files[$name])) {
            return $this->_files[$name];
        }
        return $defaultValue;
    }
    /**
     * @return string
     */
    public function getMethod() {
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        if ($method) {
            return strtoupper($method);
        }
        return 'GET';
    }
    /**
     * @return bool
     */
    public function getIsGet() {
        return $this->getMethod() === 'GET';
    }
    /**
     * @return bool
     */
    public function getIsPost() {
        return $this->getMethod() === 'POST';
    }
}