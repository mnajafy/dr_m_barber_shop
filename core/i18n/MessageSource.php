<?php
namespace core\i18n;
use core\base\BaseObject;
class MessageSource extends BaseObject {
    private $_messages = [];
    public function loadMessages($category, $language) {
        return [];
    }
    public function translate($category, $message, $language) {
        $key = $language . '/' . $category;
        if (!isset($this->_messages[$key])) {
            $this->_messages[$key] = $this->loadMessages($category, $language);
        }
        if (isset($this->_messages[$key][$message]) && $this->_messages[$key][$message] !== '') {
            return $this->_messages[$key][$message];
        }
        return $this->_messages[$key][$message] = false;
    }
}