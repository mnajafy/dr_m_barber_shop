<?php
namespace Core;
use Exception;
/**
 * View
 */
class View extends BaseObject {
    /**
     * @var string
     */
    public $title;
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
}