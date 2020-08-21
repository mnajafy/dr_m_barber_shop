<?php
namespace core\web;
use Exception;
use Framework;
use ReflectionMethod;
use core\base\BaseObject;
class Controller extends BaseObject {
    /**
     * @var string
     */
    public $id;
    /**
     * @var Module
     */
    public $module;
    /**
     * @var Action
     */
    public $action;
    /**
     * @var string
     */
    public $layout;
    /**
     * @var string
     */
    public $defaultAction = 'index';
    /**
     * 
     */
    public function runAction($actionID, $params) {
        $this->action = $this->createAction($actionID);
        return $this->action->run($params);
    }
    /**
     * @param string $actionID
     * @return Action
     */
    public function createAction($actionID) {
        if ($actionID === '') {
            $actionID = $this->defaultAction;
        }

        $methodName = 'action' . str_replace(' ', '', ucwords(str_replace('-', ' ', $actionID)));
        if (!method_exists($this, $methodName)) {
            throw new Exception("Action { <b>$actionID</b> } Not Found");
        }

        $method = new ReflectionMethod($this, $methodName);
        if (!$method->isPublic()) {
            throw new Exception("Action { <b>$actionID</b> } Not Found");
        }

        return BaseObject::createObject([
                    'class'        => Action::class,
                    'id'           => $actionID,
                    'actionMethod' => $methodName,
                    'controller'   => $this,
        ]);
    }
    /**
     * @param array|object $params
     * @return string
     */
    public function render($params = null) {
        $config = [];
        if (is_array($params)) {
            $config = $params;
        }
        elseif (is_object($params)) {
            $config['model'] = $params;
        }
        $viewFile = $this->getViewFile();
        $content  = Framework::$app->view->renderFile($viewFile, $config);
        return $this->renderLayout(['content' => $content]);
    }
    /**
     * @param array $params
     * @return string
     */
    public function renderLayout($params = []) {
        $layoutFile = $this->getLayoutFile();
        if ($layoutFile === false) {
            return $params['content'];
        }
        return Framework::$app->view->renderFile($layoutFile, $params);
    }
    /**
     * @return string
     */
    public function getViewFile() {
        $file     = $this->module->getViewPath() . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . $this->action->id . '.php';
        $viewFile = realpath($file);
        if (!is_file($viewFile)) {
            throw new Exception("View File { <b>{$file}</b> } Not Found");
        }
        return $viewFile;
    }
    /**
     * @return false|string
     */
    public function getLayoutFile() {
        $layout = false;
        $module = $this->module;
        if (!is_null($this->layout)) {
            $layout = $this->layout;
        }
        else if (is_null($this->layout) && !is_null($this->module)) {
            while ($module !== null && $module->layout === null) {
                $module = $module->module;
            }
            if ($module !== null && is_string($module->layout)) {
                $layout = $module->layout;
            }
        }
        if ($layout === false) {
            return false;
        }
        if (strncmp($layout, '@', 1) === 0) {
            $file = Framework::getAlias($layout);
        }
        elseif (strncmp($layout, '/', 1) === 0) {
            $file = Framework::$app->getLayoutPath() . DIRECTORY_SEPARATOR . substr($layout, 1);
        }
        else {
            $file = $module->getLayoutPath() . DIRECTORY_SEPARATOR . $layout;
        }
        $layoutFile = realpath($file . '.php');
        if (!is_file($layoutFile)) {
            throw new Exception("Layout File { <b>$file.php</b> } Not Found");
        }
        return $layoutFile;
    }
    //
    public function getUniqueId() {
        return $this->module instanceof Application ? $this->id : $this->module->getUniqueId() . '/' . $this->id;
    }
    public function getRoute() {
        return $this->action !== null ? $this->action->getUniqueId() : $this->getUniqueId();
    }
    public function redirect() {
        
    }
}