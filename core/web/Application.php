<?php
namespace core\web;
//use Exception;
use Framework;
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
    private $_request;
    private $_response;
    private $_urlManager;
    private $_session;
    private $_cookie;
    private $_view;
    private $_user;
    private $_db;
    public function __construct($config = []) {
//        ini_set('display_errors', false);
//        set_error_handler(function ($severity, $message, $file, $line) {
//            for ($level = ob_get_level(); $level > 0; --$level) {
//                if (!@ob_end_clean()) {
//                    ob_clean();
//                }
//            }
//            $response       = Framework::$app->response;
//            $response->code = 500;
//            $response->data = Framework::$app->runAction('error/index', ['title' => 'Error', 'file' => $file, 'line' => $line, 'message' => $message]);
//            $response->send();
//            exit;
//        });
//        set_exception_handler(function ($exception) {
//            for ($level = ob_get_level(); $level > 0; --$level) {
//                if (!@ob_end_clean()) {
//                    ob_clean();
//                }
//            }
//            $response       = Framework::$app->response;
//            $response->code = 500;
//            $response->data = Framework::$app->runAction('error/index', ['title' => 'Error', 'file' => $exception->getFile(), 'line' => $exception->getLine(), 'message' => $exception->getMessage()]);
//            $response->send();
//            exit;
//        });
//        register_shutdown_function(function () {
//            for ($level = ob_get_level(); $level > 0; --$level) {
//                if (!@ob_end_clean()) {
//                    ob_clean();
//                }
//            }
//            $error          = error_get_last();
//            $response       = Framework::$app->response;
//            $response->code = 500;
//            $response->data = Framework::$app->runAction('error/index', [
//                'title'   => 'Error',
//                'file'    => $error['file'],
//                'line'    => $error['line'],
//                'message' => $error['message']
//            ]);
//            $response->send();
//            exit;
//        });
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
    /**
     * @return Request
     */
    public function getRequest() {
        return $this->_request;
    }
    public function setResponse($value) {
        $this->_response = BaseObject::createObject($value);
    }
    /**
     * @return Response
     */
    public function getResponse() {
        return $this->_response;
    }
    public function setUrlManager($value) {
        $this->_urlManager = BaseObject::createObject($value);
    }
    /**
     * @return UrlManager
     */
    public function getUrlManager() {
        return $this->_urlManager;
    }
    public function setSession($value) {
        $this->_session = BaseObject::createObject($value);
    }
    /**
     * @return Session
     */
    public function getSession() {
        return $this->_session;
    }
    public function setCookie($value) {
        $this->_cookie = BaseObject::createObject($value);
    }
    /**
     * @return Cookie
     */
    public function getCookie() {
        return $this->_cookie;
    }
    public function setView($value) {
        $this->_view = BaseObject::createObject($value);
    }
    /**
     * @return View
     */
    public function getView() {
        return $this->_view;
    }
    public function setUser($value) {
        $this->_user = BaseObject::createObject($value);
    }
    /**
     * @return User
     */
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
    public function run() {
        $response = $this->handleRequest($this->getRequest());
        $response->send();
    }
    /**
     * @param Request $request
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