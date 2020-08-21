<?php
namespace core\web;
use Exception;
use Framework;
use core\base\BaseObject;
/**
 * Request
 * 
 * @property-read string $pathInfo
 * @property-read string $method
 * @property-read bool $isGet
 * @property-read bool $isPost
 * @property-read string $baseUrl
 * @property-read string $scriptUrl
 * @property-read string $scriptFile
 */
class Request extends BaseObject {
    private $_get;
    private $_post;
    private $_files;
    private $_request_method;
    private $_baseUrl;
    private $_scriptUrl;
    private $_scriptFile;
    public function init() {
        $this->_get            = $_GET;
        $this->_post           = $_POST;
        $this->_files          = $_FILES;
        $this->_request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        Framework::setAlias('@web', $this->getBaseUrl());
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
     * @return array
     */
    public function resolve() {
        list($route, $params) = Framework::$app->getUrlManager()->parseRequest($this);
        $this->merge($params);
        return [$route, $this->_get];
    }
    /**
     * @return string
     */
    public function getPathInfo() {
        $pathInfo = $_SERVER['REQUEST_URI'];
        if (($pos      = strpos($pathInfo, '?')) !== false) {
            $pathInfo = substr($pathInfo, 0, $pos);
        }
        $pathInfo  = urldecode($pathInfo);
        $scriptUrl = $this->getScriptUrl();
        $baseUrl   = rtrim(dirname($this->getScriptUrl()), '\\/');
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
    /**
     * @return string
     */
    public function getBaseUrl() {
        if ($this->_baseUrl === null) {
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()), '\\/');
        }
        return $this->_baseUrl;
    }
    public function setBaseUrl($value) {
        $this->_baseUrl = $value;
    }
    /**
     * @return string
     */
    public function getScriptUrl() {
        if ($this->_scriptUrl === null) {
            $scriptFile = $this->getScriptFile();
            $scriptName = basename($scriptFile);
            if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['SCRIPT_NAME'];
            }
            elseif (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['PHP_SELF'];
            }
            elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
            }
            elseif (isset($_SERVER['PHP_SELF']) && ($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
                $this->_scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            }
            elseif (!empty($_SERVER['DOCUMENT_ROOT']) && strpos($scriptFile, $_SERVER['DOCUMENT_ROOT']) === 0) {
                $this->_scriptUrl = str_replace([$_SERVER['DOCUMENT_ROOT'], '\\'], ['', '/'], $scriptFile);
            }
            else {
                throw new Exception('Unable to determine the entry script URL.');
            }
        }
        return $this->_scriptUrl;
    }
    public function setScriptUrl($value) {
        $this->_scriptUrl = $value === null ? null : '/' . trim($value, '/');
    }
    /**
     * @return string
     */
    public function getScriptFile() {
        if (isset($this->_scriptFile)) {
            return $this->_scriptFile;
        }
        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            return $_SERVER['SCRIPT_FILENAME'];
        }
        throw new InvalidConfigException('Unable to determine the entry script file path.');
    }
    public function setScriptFile($value) {
        $this->_scriptFile = $value;
    }
}