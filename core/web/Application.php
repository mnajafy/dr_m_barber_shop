<?php
namespace core\web;
use Framework;
use core\i18n\I18N;
use core\db\Database;
use core\helpers\ArrayHelper;
/**
 * Application
 * 
 * @property-read I18N $i18n
 * @property-read ErrorHandler $errorHandler
 * @property-read Request $request
 * @property-read Response $response
 * @property-read UrlManager $urlManager
 * @property-read Session $session
 * @property-read Cookie $cookie
 * @property-read View $view
 * @property-read User $user
 * @property-read Database $db
 * @property-read Security $security
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
    /**
     * @var string
     */
    public $language            = 'fa-IR';
    /**
     * @param array $config
     */
    public function __construct($config = []) {
        Framework::$app = $this;
        $this->preInit($config);
        $this->registerErrorHandler($config);
        parent::__construct($config);
    }
    /**
     * @param array $config
     */
    public function preInit(&$config = []) {
        $core   = [
            'services' => [
                'i18n'         => ['class' => '\core\i18n\I18n'],
                'errorHandler' => ['class' => '\core\web\ErrorHandler'],
                'request'      => ['class' => '\core\web\Request'],
                'response'     => ['class' => '\core\web\Response'],
                'urlManager'   => ['class' => '\core\web\UrlManager'],
                'session'      => ['class' => '\core\web\Session'],
                'cookie'       => ['class' => '\core\web\Cookie'],
                'view'         => ['class' => '\core\web\View'],
                'user'         => ['class' => '\core\web\User'],
                'db'           => ['class' => '\core\db\Database'],
                'security'     => ['class' => '\core\web\Security'],
            ]
        ];
        $config = ArrayHelper::merge($core, $config);
    }
    /**
     * @param array $config
     */
    public function registerErrorHandler(&$config) {
        $this->set('errorHandler', $config['services']['errorHandler']);
        unset($config['services']['errorHandler']);
        $this->getErrorHandler()->register();
    }
    /**
     * 
     */
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
    /**
     * @return string
     */
    public function getUniqueId() {
        return '';
    }
    /**
     * @param string $path
     */
    public function setBasePath($path) {
        parent::setBasePath($path);
        Framework::setAlias('@app', $this->getBasePath());
    }
    /**
     * @return I18N
     */
    public function getI18n() {
        return $this->get('i18n');
    }
    /**
     * @return ErrorHandler
     */
    public function getErrorHandler() {
        return $this->get('errorHandler');
    }
    /**
     * @return Request
     */
    public function getRequest() {
        return $this->get('request');
    }
    /**
     * @return Response
     */
    public function getResponse() {
        return $this->get('response');
    }
    /**
     * @return UrlManager
     */
    public function getUrlManager() {
        return $this->get('urlManager');
    }
    /**
     * @return Session
     */
    public function getSession() {
        return $this->get('session');
    }
    /**
     * @return Cookie
     */
    public function getCookie() {
        return $this->get('cookie');
    }
    /**
     * @return View
     */
    public function getView() {
        return $this->get('view');
    }
    /**
     * @return User
     */
    public function getUser() {
        return $this->get('user');
    }
    /**
     * @return Database
     */
    public function getDb() {
        return $this->get('db');
    }
    /**
     * @return Security
     */
    public function getSecurity() {
        return $this->get('security');
    }
}