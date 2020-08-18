<?php
namespace core\web;
use Framework;
use Exception;
use core\base\BaseObject;
/**
 * Application
 * 
 * @property Request $request
 * @property Response $response
 * @property UrlManager $urlManager
 * @property Session $session
 * @property Cookie $cookie
 * @property View $view
 * @property User $user
 * @property \core\db\Database $db
 * 
 * @property string $layoutPath
 * @property string $basePath
 * @property string $viewPath
 * @property string $layout
 */
class Application extends BaseObject {
    /**
     * @var string
     */
    public $controllerNamespace = '\app\controllers';
    /**
     * @var Controller
     */
    public $controller;
    //
    private $_request;
    private $_response;
    private $_urlManager;
    private $_session;
    private $_cookie;
    private $_view;
    private $_user;
    private $_db;
    private $_layoutPath;
    private $_basePath;
    private $_viewPath;
    private $_layout            = 'main';
    public function __construct($config = []) {
        set_exception_handler(function ($exception) {
            for ($level = ob_get_level(); $level > 0; --$level) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            $response       = Framework::$app->response;
            $response->code = 500;
            $response->data = Framework::$app->runAction('error/index', ['title' => 'Error', 'file' => $exception->getFile(), 'line' => $exception->getLine(), 'message' => $exception->getMessage()]);
            $response->send();
            exit;
        });
        set_error_handler(function ($severity, $message, $file, $line) {
            for ($level = ob_get_level(); $level > 0; --$level) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            $response       = Framework::$app->response;
            $response->code = 500;
            $response->data = Framework::$app->runAction('error/index', ['title' => 'Error', 'file' => $file, 'line' => $line, 'message' => $message]);
            $response->send();
            exit;
        });
        Framework::$app = $this;
        $this->preInit($config);
        parent::__construct($config);
    }
    public function preInit(&$config = []) {
        $core   = [
            'request'    => ['class' => '\core\web\Request'],
            'response'   => ['class' => '\core\web\Response'],
            'urlManager' => ['class' => '\core\web\UrlManager'],
            'session'    => ['class' => '\core\web\Session'],
            'cookie'     => ['class' => '\core\web\Cookie'],
            'view'       => ['class' => '\core\web\View'],
            'user'       => ['class' => '\core\web\User'],
            'db'         => ['class' => '\core\db\Database'],
        ];
        $config = array_merge($core, $config);
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
    public function setUrlManager($value) {
        $this->_urlManager = BaseObject::createObject($value);
    }
    public function getUrlManager() {
        return $this->_urlManager;
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
    public function setView($value) {
        $this->_view = BaseObject::createObject($value);
    }
    public function getView() {
        return $this->_view;
    }
    public function setUser($value) {
        $this->_user = BaseObject::createObject($value);
    }
    public function getUser() {
        return $this->_user;
    }
    public function setDb($value) {
        $this->_db = BaseObject::createObject($value);
    }
    /**
     * @return \core\db\Database
     */
    public function getDb() {
        return $this->_db;
    }
    //
    public function setLayoutPath($value) {
        $this->_layoutPath = $value;
    }
    public function getLayoutPath() {
        return $this->_layoutPath;
    }
    public function setBasePath($value) {
        $this->_basePath = $value;
    }
    public function getBasePath() {
        return $this->_basePath;
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
    //
    public function run() {
        $this->handleRequest()->send();
    }
    public function handleRequest() {
        list($route, $params) = $this->request->resolve();
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
        if (!class_exists($className) || !is_subclass_of($className, '\core\web\Controller')) {
            throw new Exception("Controller { <b>$controllerID</b> } Not Found");
        }
        return BaseObject::createObject(['class' => $className, 'id' => $controllerID]);
    }
}