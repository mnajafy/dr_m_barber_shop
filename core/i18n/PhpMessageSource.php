<?php
namespace core\i18n;
use Framework;
class PhpMessageSource extends MessageSource {
    public $basePath;
    public function loadMessages($category, $language) {
        $messageFile = $this->getMessageFilePath($category, $language);
        return $this->loadMessagesFromFile($messageFile);
    }
    public function getMessageFilePath($category, $language) {
        $messageFile = Framework::getAlias($this->basePath) . "/$language/";
        $messageFile .= str_replace('\\', '/', $category) . '.php';
        return realpath($messageFile);
    }
    public function loadMessagesFromFile($messageFile) {
        if ($messageFile !== false && is_file($messageFile)) {
            $messages = include $messageFile;
            if (!is_array($messages)) {
                $messages = [];
            }
            return $messages;
        }
        return [];
    }
}