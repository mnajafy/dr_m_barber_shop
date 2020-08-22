<?php
namespace core\web;
use Exception;
use core\base\BaseObject;
use core\helpers\ArrayHelper;
/**
 * @property-read bool $isActive
 * @property array $cookieParams
 * @property string $name
 */
class Session extends BaseObject {
    private $_cookieParams = [
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => false,
        'samesite' => 'Strict',
        'httponly' => true,
    ];
    public $frozenSessionData;
    public function init() {
        parent::init();
        register_shutdown_function([$this, 'close']);
    }
    //
    public function open() {
        if ($this->getIsActive()) {
            return;
        }
        $this->setCookieParamsInternal();
        session_start();
    }
    public function close() {
        if ($this->getIsActive()) {
            session_write_close();
        }
    }
    public function set($key, $value) {
        $this->open();
        $_SESSION[$key] = $value;
    }
    public function get($key, $defaultValue = null) {
        $this->open();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
    }
    public function has($key) {
        $this->open();
        return isset($_SESSION[$key]);
    }
    public function remove($key) {
        $this->open();
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }
        return null;
    }
    public function removeAll() {
        $this->open();
        foreach (array_keys($_SESSION) as $key) {
            unset($_SESSION[$key]);
        }
    }
    //
    public function setCookieParamsInternal() {
        $data = $this->getCookieParams();
        if (isset($data['lifetime'], $data['path'], $data['domain'], $data['secure'], $data['httponly'])) {
            if (PHP_VERSION_ID >= 70300) {
                session_set_cookie_params($data);
            }
            else {
                session_set_cookie_params($data['lifetime'], $data['path'], $data['domain'], $data['secure'], $data['httponly']);
            }
        }
        else {
            throw new Exception('Please make sure cookieParams contains these elements: lifetime, path, domain, secure and httponly.');
        }
    }
    public function freeze() {
        if ($this->getIsActive()) {
            if (isset($_SESSION)) {
                $this->frozenSessionData = $_SESSION;
            }
            $this->close();
        }
    }
    public function unfreeze() {
        if (null !== $this->frozenSessionData) {
            session_start();
            $_SESSION                = $this->frozenSessionData;
            $this->frozenSessionData = null;
        }
    }
    //
    public function getIsActive() {
        return session_status() === PHP_SESSION_ACTIVE;
    }
    public function getName() {
        return session_name();
    }
    public function setName($value) {
        $this->freeze();
        session_name($value);
        $this->unfreeze();
    }
    public function getCookieParams() {
        return ArrayHelper::merge(session_get_cookie_params(), $this->_cookieParams);
    }
    public function setCookieParams($value) {
        return ArrayHelper::merge($this->getCookieParams(), array_change_key_case($value));
    }
}