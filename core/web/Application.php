<?php
namespace core\web;
use Framework;
use core\base\BaseObject;
use core\helpers\ArrayHelper;
/**
 * Application
 * 
 * @property ErrorHandler $errorHandler
 * @property Request $request
 * @property Response $response
 * @property UrlManager $urlManager
 * @property Session $session
 * @property Cookie $cookie
 * @property View $view
 * @property User $user
 * @property \core\db\Database $db
 * 
 */
class Application extends Module {
    /**
     * @var string
     */
    public $controllerNamespace = 'app\controllers';
    /**
     * @var string
     */
    public $layout              = 'main';
    /**
     * @var Controller
     */
    public $controller;
    //
    private $_errorHandler;
    private $_request;
    private $_response;
    private $_urlManager;
    private $_session;
    private $_cookie;
    private $_view;
    private $_user;
    private $_db;
    public function __construct($config = []) {
        Framework::$app = $this;
        $this->preInit($config);
        $this->registerErrorHandler($config);
        parent::__construct($config);
    }
    public function preInit(&$config = []) {
        $core   = [
            'errorHandler' => ['class' => '\core\web\ErrorHandler'],
            'request'      => ['class' => '\core\web\Request'],
            'response'     => ['class' => '\core\web\Response'],
            'urlManager'   => ['class' => '\core\web\UrlManager'],
            'session'      => ['class' => '\core\web\Session'],
            'cookie'       => ['class' => '\core\web\Cookie'],
            'view'         => ['class' => '\core\web\View'],
            'user'         => ['class' => '\core\web\User'],
            'db'           => ['class' => '\core\db\Database'],
        ];
        $config = ArrayHelper::merge($core, $config);
    }
    public function registerErrorHandler(&$config) {
        $this->setErrorHandler($config['errorHandler']);
        unset($config['errorHandler']);
        $this->getErrorHandler()->register();
    }
    public function setErrorHandler($value) {
        $this->_errorHandler = $value;
    }
    /**
     * @return ErrorHandler
     */
    public function getErrorHandler() {
        if (!is_object($this->_errorHandler)) {
            $this->_errorHandler = BaseObject::createObject($this->_errorHandler);
        }
        return $this->_errorHandler;
    }
    public function setRequest($value) {
        $this->_request = $value;
    }
    /**
     * @return Request
     */
    public function getRequest() {
        if (!is_object($this->_request)) {
            $this->_request = BaseObject::createObject($this->_request);
        }
        return $this->_request;
    }
    public function setResponse($value) {
        $this->_response = $value;
    }
    /**
     * @return Response
     */
    public function getResponse() {
        if (!is_object($this->_response)) {
            $this->_response = BaseObject::createObject($this->_response);
        }
        return $this->_response;
    }
    public function setUrlManager($value) {
        $this->_urlManager = $value;
    }
    /**
     * @return UrlManager
     */
    public function getUrlManager() {
        if (!is_object($this->_urlManager)) {
            $this->_urlManager = BaseObject::createObject($this->_urlManager);
        }
        return $this->_urlManager;
    }
    public function setSession($value) {
        $this->_session = $value;
    }
    /**
     * @return Session
     */
    public function getSession() {
        if (!is_object($this->_session)) {
            $this->_session = BaseObject::createObject($this->_session);
        }
        return $this->_session;
    }
    public function setCookie($value) {
        $this->_cookie = $value;
    }
    /**
     * @return Cookie
     */
    public function getCookie() {
        if (!is_object($this->_cookie)) {
            $this->_cookie = BaseObject::createObject($this->_cookie);
        }
        return $this->_cookie;
    }
    public function setView($value) {
        $this->_view = $value;
    }
    /**
     * @return View
     */
    public function getView() {
        if (!is_object($this->_view)) {
            $this->_view = BaseObject::createObject($this->_view);
        }
        return $this->_view;
    }
    public function setUser($value) {
        $this->_user = $value;
    }
    /**
     * @return User
     */
    public function getUser() {
        if (!is_object($this->_user)) {
            $this->_user = BaseObject::createObject($this->_user);
        }
        return $this->_user;
    }
    public function setDb($value) {
        $this->_db = $value;
    }
    /**
     * @return \core\db\Database
     */
    public function getDb() {
        if (!is_object($this->_db)) {
            $this->_db = BaseObject::createObject($this->_db);
        }
        return $this->_db;
    }
    //
    public function run() {
        $response = $this->handleRequest($this->getRequest());
        $response->send();
    }
    /**
     * @param Request $request Request
     * @return Response Response
     */
    public function handleRequest($request) {
        list($route, $params) = $request->resolve();
        $result = $this->runAction($route, $params);
        if ($result instanceof Response) {
            return $result;
        }
        $response       = $this->getResponse();
        $response->data = $result;
        return $response;
    }
}