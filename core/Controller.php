<?php
namespace Core;
use Framework;
use Exception;
use ReflectionMethod;
class Controller extends BaseObject {
    /**
     * @var string
     */
    public $id;
    /**
     * @var Action
     */
    public $action;
    /**
     * @var string
     */
    public $layout;
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
    public function createAction(string $actionID) {
        $methodName = 'action' . str_replace(' ', '', ucwords(str_replace('-', ' ', $actionID)));
        if (method_exists($this, $methodName)) {
            $method = new ReflectionMethod($this, $methodName);
            if ($method->isPublic()) {
                return BaseObject::createObject([
                            'class'        => Action::class,
                            'id'           => $actionID,
                            'actionMethod' => $methodName,
                            'controller'   => $this,
                ]);
            }
        }
        throw new Exception("Action { <b>$actionID</b> } Not Found");
    }
    /**
     * @param array $params
     * @return string
     */
    public function render($params = []) {
        $viewFile = $this->getViewFile();
        $content  = Framework::$app->view->renderFile($viewFile, $params);
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
        return Framework::$app->viewPath
                . DIRECTORY_SEPARATOR . $this->id
                . DIRECTORY_SEPARATOR . $this->action->id
                . '.php';
    }
    /**
     * @return false|string
     */
    public function getLayoutFile() {
        $layout = false;
        if (is_string($this->layout)) {
            $layout = $this->layout;
        }
        else if (is_null($this->layout) && !is_null(Framework::$app->layout)) {
            $layout = Framework::$app->layout;
        }

        if ($layout === false) {
            return false;
        }

        $layoutFile = Framework::$app->layoutPath . DIRECTORY_SEPARATOR . $layout . '.php';
        if (!is_file($layoutFile)) {
            throw new Exception("Layout File { <b>$layoutFile</b> } Not Found");
        }

        return $layoutFile;
    }
}