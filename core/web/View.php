<?php
namespace core\web;
use Exception;
use Framework;
use core\base\BaseObject;
class View extends BaseObject {
    /**
     * @var string
     */
    public $title;
    /**
     * @var array
     */
    public $params = [];
    /**
     * @var array
     */
    public $assets = [];
    /**
     * @param string $_file_
     * @param array $_params_
     * @return string
     */
    public function renderFile($_file_, $_params_ = []) {
        $this->_viewFiles[] = $_file_;
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        require $_file_;
        $output = ob_get_clean();
        array_pop($this->_viewFiles);
        return $output;
    }
    /**
     * @param string $name
     */
    public function registerAssetBundle($name) {
        if (isset($this->assets[$name])) {
            return;
        }
        $this->assets[$name] = BaseObject::createObject(['class' => $name]);
    }
    /**
     * @return string
     */
    public function head() {
        $css = [];
        foreach ($this->assets as $asset) {
            /* @var $asset AssetBundle */
            foreach ($asset->css as $cssLink) {
                $css[] = '<link rel="stylesheet" type="text/css" href="' . Framework::getAlias('@web/' . $cssLink) . '"/>';
            }
        }
        return "\n        " . implode("\n        ", $css) . "\n";
    }
    /**
     * @return string
     */
    public function body() {
        $js = [];
        foreach ($this->assets as $asset) {
            /* @var $asset AssetBundle */
            foreach ($asset->js as $jsLink) {
                $js[] = '<script type="text/javascript" src="' . Framework::getAlias('@web/' . $jsLink) . '"></script>';
            }
        }
        return "\n        " . implode("\n        ", $js) . "\n";
    }
    /**
     * 
     */
    public function clear() {
        $this->title  = null;
        $this->params = [];
        $this->assets = [];
    }
    //
    public function render($view, $params = []) {
        $viewFile = $this->findViewFile($view);
        return $this->renderFile($viewFile, $params);
    }
    public function findViewFile($view) {
        if (strncmp($view, '@', 1) === 0) {
            // e.g. "@app/views/main"
            $file = Framework::getAlias($view);
        }
        elseif (strncmp($view, '//', 2) === 0) {
            // e.g. "//layouts/main"
            $file = Framework::$app->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
        }
        elseif (strncmp($view, '/', 1) === 0) {
            // e.g. "/site/index"
            if (Framework::$app->controller === null) {
                throw new Exception("Unable to locate view file for view '$view': no active controller.");
            }
            $file = Framework::$app->controller->module->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
        }
        elseif (($currentViewFile = $this->getRequestedViewFile()) !== false) {
            $file = dirname($currentViewFile) . DIRECTORY_SEPARATOR . $view;
        }
        else {
            throw new Exception("Unable to resolve view file for view '$view': no active view context.");
        }
        $viewFile = realpath($file . '.php');
        if ($viewFile === false) {
            throw new Exception("View File { <b>$file.php</b> } Not Found");
        }
        return $file . '.php';
    }
    private $_viewFiles = [];
    public function getRequestedViewFile() {
        return empty($this->_viewFiles) ? false : end($this->_viewFiles);
    }
}