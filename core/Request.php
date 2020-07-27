<?php
namespace Core;
use Framework;
/**
 * Request
 * 
 * @property-read string $pathInfo
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
        $this->_get            = $_GET;
        $this->_post           = $_POST;
        $this->_files          = $_FILES;
        $this->_request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $this->_script_name    = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
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
    public function resolve() {
        list($route, $params) = Framework::$app->urlManager->parseRequest($this);
        $this->merge($params);
        return [$route, $this->_get];
    }
    public function getPathInfo() {
        $pathInfo = $_SERVER['REQUEST_URI'];
        if (($pos      = strpos($pathInfo, '?')) !== false) {
            $pathInfo = substr($pathInfo, 0, $pos);
        }
        $pathInfo  = urldecode($pathInfo);
        $scriptUrl = $this->_script_name;
        $baseUrl   = rtrim(dirname($this->_script_name), '\\/');
        if (strpos($pathInfo, $scriptUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($scriptUrl));
        }
        elseif ($baseUrl === '' || strpos($pathInfo, $baseUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($baseUrl));
        }
        elseif (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], $scriptUrl) === 0) {
            $pathInfo = substr($_SERVER['PHP_SELF'], strlen($scriptUrl));
        }
        if (strncmp($pathInfo, '/', 1) === 0) {
            $pathInfo = substr($pathInfo, 1);
        }
        return (string) $pathInfo;
    }
    /**
     * @return string
     */
    public function getMethod() {
        return $this->_request_method;
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