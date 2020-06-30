<?php
namespace Core;
use Exception;
use Framework;
/**
 * View
 */
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
        if (!is_file($_file_)) {
            throw new Exception("View File { <b>$_file_</b> } Not Found");
        }
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        require $_file_;
        return ob_get_clean();
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
}