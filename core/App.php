<?php
namespace Core;
use Framework;
use Exception;
/**
 * Application
 * 
 * @property Session $session
 * @property Cookie $cookie
 * @property User $user
 * @property Request $request
 * @property Response $response
 * @property View $view
 * @property Database $db
 * @property string $basePath
 * @property UrlManager $urlManager
 * @property string $layoutPath
 * @property string $viewPath
 */
class App extends BaseObject {
    /**
     * @var string
     */
    public $controllerNamespace = '\App\Controllers';
    /**
     * @var Controller
     */
    public $controller;
    private $_session;
    private $_cookie;
    private $_user;
    private $_request;
    private $_response;
    private $_view;
    private $_db;
    private $_basePath;
    private $_urlManager;
    private $_layoutPath;
    private $_viewPath;
    private $_layout = 'main';
    public function __construct($config = []) {
        Framework::$app = $this;
        $this->preInit($config);
        parent::__construct($config);
    }
    public function preInit(&$config = []) {
        $core   = [
            'session'  => ['class' => '\Core\Session'],
            'cookie'   => ['class' => '\Core\Cookie'],
            'user'     => ['class' => '\Core\User'],
            'request'  => ['class' => '\Core\Request'],
            'response' => ['class' => '\Core\Response'],
            'view'     => ['class' => '\Core\View'],
        ];
        $config = array_merge($core, $config);
    }
    public function setSession($value) {
        $this->_session = BaseObject::createObject($value);
    }
    public function getSession() {
        return $this->_session;
    }
    public function setCookie($value) {
        $this->_cookie = BaseObject::createObject($value);
    }
    public function getCookie() {
        return $this->_cookie;
    }
    public function setUser($value) {
        $this->_user = BaseObject::createObject($value);
    }
    public function getUser() {
        return $this->_user;
    }
    public function setRequest($value) {
        $this->_request = BaseObject::createObject($value);
    }
    public function getRequest() {
        return $this->_request;
    }
    public function setResponse($value) {
        $this->_response = BaseObject::createObject($value);
    }
    public function getResponse() {
        return $this->_response;
    }
    public function setView($value) {
        $this->_view = BaseObject::createObject($value);
    }
    public function getView() {
        return $this->_view;
    }
    public function setDb($value) {
        $this->_db = BaseObject::createObject($value);
    }
    public function getDb() {
        return $this->_db;
    }
    public function setBasePath($value) {
        $this->_basePath = $value;
    }
    public function getBasePath() {
        return $this->_basePath;
    }
    public function setUrlManager($value) {
        $this->_urlManager = BaseObject::createObject($value);
    }
    public function getUrlManager() {
        return $this->_urlManager;
    }
    public function setLayoutPath($value) {
        $this->_layoutPath = $value;
    }
    public function getLayoutPath() {
        return $this->_layoutPath;
    }
    public function setViewPath($value) {
        $this->_viewPath = $value;
    }
    public function getViewPath() {
        return $this->_viewPath;
    }
    public function setLayout($value) {
        $this->_layout = $value;
    }
    public function getLayout() {
        return $this->_layout;
    }
    public function run() {
        error_reporting(0);
        set_error_handler(function ($severity, $message, $file, $line) {
            $response       = Framework::$app->response;
            $response->code = 500;
            $response->data = Framework::$app->runAction('error/index', ['title' => 'Error', 'message' => $file . ': ' . $line . '<br/>Error: ' . $message]);
            $response->send();
            exit;
        });
        try {
            $response = $this->handleRequest();
            $response->send();
        } catch (Exception $ex) {
            $response       = $this->response;
            $response->code = 500;
            $response->data = Framework::$app->runAction('error/index', ['title' => 'Error', 'message' => $ex->getFile() . ': ' . $ex->getLine() . '<br/>Error: ' . $ex->getMessage()]);
            $response->send();
        }
    }
    public function handleRequest() {
        list($route, $params) = $this->urlManager->resolve($this->request);
        $result = $this->runAction($route, $params);
        if ($result instanceof Response) {
            return $result;
        }
        $response       = $this->response;
        $response->data = $result;
        return $response;
    }
    public function runAction($route, $params) {
        list($controllerID, $actionID) = explode('/', $route);
        $this->controller = $this->createController($controllerID);
        return $this->controller->runAction($actionID, $params);
    }
    public function createController($controllerID) {
        $controllerName = str_replace(' ', '', ucwords(str_replace('-', ' ', $controllerID)));
        $className      = $this->controllerNamespace . '\\' . $controllerName . 'Controller';
        if (!class_exists($className) || !is_subclass_of($className, 'Core\Controller')) {
            throw new Exception("Controller { <b>$controllerID</b> } Not Found");
        }
        return BaseObject::createObject(['class' => $className, 'id' => $controllerID]);
    }
}