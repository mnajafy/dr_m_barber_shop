<?php
namespace core\web;
use Exception;
use Framework;
use core\base\BaseObject;
/**
 * 
 * @property string $layoutPath
 * @property string $basePath
 * @property string $viewPath
 */
class Module extends Services {
    //
    public $id;
    public $controllerNamespace;
    public $layout;
    public $defaultRoute = 'default';
    //
    private $_basePath;
    private $_viewPath;
    private $_layoutPath;
    /**
     * @var Module the parent module of this module. `null` if this module does not have a parent.
     */
    public $module;
    /**
     * @var array
     */
    public $_modules     = [];
    //
    public function init() {
        if ($this->controllerNamespace === null) {
            $class = get_class($this);
            if (($pos   = strrpos($class, '\\')) !== false) {
                $this->controllerNamespace = substr($class, 0, $pos) . '\\controllers';
            }
        }
    }
    //
    public function runAction($route, $params) {
        $parts = $this->createController($route);
        if ($parts === false) {
            throw new Exception('Unable to resolve the request');
        }
        /* @var $controller Controller */
        list($controller, $actionID) = $parts;
        Framework::$app->controller = $controller;
        return $controller->runAction($actionID, $params);
    }
    public function createController($route) {
        if ($route === '') {
            $route = $this->defaultRoute;
        }

        $route = trim($route, '/');
        if (strpos($route, '/') === false) {
            $id    = $route;
            $route = '';
        }
        else {
            list($id, $route) = explode('/', $route, 2);
        }

        $module = $this->getModule($id);
        if ($module !== null) {
            return $module->createController($route);
        }

        $controller = $this->createControllerByID($id);
        return $controller === null ? false : [$controller, $route];
    }
    public function createControllerByID($id) {
        $className = preg_replace_callback('%-([a-z0-9_])%i', function ($matches) {
                    return ucfirst($matches[1]);
                }, ucfirst($id)) . 'Controller';
        $className = ltrim($this->controllerNamespace . '\\' . $className, '\\');
        if (!class_exists($className) || !is_subclass_of($className, Controller::class)) {
            throw new Exception("Controller { <b>$className</b> } Not Found");
        }
        $controller = BaseObject::createObject(['class' => $className, 'id' => $id, 'module' => $this]);
        return get_class($controller) === $className ? $controller : null;
    }
    //
    public function hasModule($id) {
        if (($pos = strpos($id, '/')) !== false) {
            // sub-module
            $module = $this->getModule(substr($id, 0, $pos));
            return $module === null ? false : $module->hasModule(substr($id, $pos + 1));
        }
        return isset($this->_modules[$id]);
    }
    public function getModule($id) {
        if (($pos = strpos($id, '/')) !== false) {
            // sub-module
            $module = $this->getModule(substr($id, 0, $pos));
            return $module === null ? null : $module->getModule(substr($id, $pos + 1));
        }
        if (isset($this->_modules[$id])) {
            if ($this->_modules[$id] instanceof self) {
                return $this->_modules[$id];
            }
            $this->_modules[$id] = BaseObject::createObject(array_merge($this->_modules[$id], [
                        'id'     => $id,
                        'module' => $this
            ]));
            return $this->_modules[$id];
        }
        return null;
    }
    public function setModule($id, $module) {
        if ($module === null) {
            unset($this->_modules[$id]);
        }
        else {
            $this->_modules[$id] = $module;
        }
    }
    public function getModules() {
        return $this->_modules;
    }
    public function setModules($modules) {
        foreach ($modules as $id => $module) {
            $this->_modules[$id] = $module;
        }
    }
    //
    public function setLayoutPath($value) {
        $this->_layoutPath = $value;
    }
    public function getLayoutPath() {
        if ($this->_layoutPath === null) {
            $this->_layoutPath = $this->getViewPath() . DIRECTORY_SEPARATOR . 'layouts';
        }
        return $this->_layoutPath;
    }
    public function setBasePath($value) {
        $this->_basePath = $value;
    }
    public function getBasePath() {
        if ($this->_basePath === null) {
            $class           = new \ReflectionClass($this);
            $this->_basePath = dirname($class->getFileName());
        }
        return $this->_basePath;
    }
    public function setViewPath($value) {
        $this->_viewPath = $value;
    }
    public function getViewPath() {
        if ($this->_viewPath === null) {
            $this->_viewPath = $this->getBasePath() . DIRECTORY_SEPARATOR . 'views';
        }
        return $this->_viewPath;
    }
    public function getUniqueId() {
        return $this->module && !$this->module instanceof Application ? ltrim($this->module->getUniqueId() . '/' . $this->id, '/') : $this->id;
    }
}