<?php
namespace core\i18n;
use Exception;
use core\base\BaseObject;
class I18N extends BaseObject {
    //
    public $translations = [];
    //
    /**
     * @return MessageSource
     */
    public function getMessageSource($category) {
        if (isset($this->translations[$category])) {
            $source = $this->translations[$category];
            if ($source instanceof MessageSource) {
                return $source;
            }
            return $this->translations[$category] = BaseObject::createObject($source);
        }
        if (isset($this->translations['*'])) {
            $source = $this->translations['*'];
            if ($source instanceof MessageSource) {
                return $source;
            }
            return $this->translations[$category] = $this->translations['*']       = BaseObject::createObject($source);
        }
        throw new Exception("Unable to locate message source for category '$category'.");
    }
    public function translate($category, $message, $params, $language) {
        $messageSource = $this->getMessageSource($category);
        $translation   = $messageSource->translate($category, $message, $language);
        if ($translation === false) {
            return $this->format($message, $params, $language);
        }
        return $this->format($translation, $params, $language);
    }
    public function format($message, $params, $language) {
        $params = (array) $params;
        if ($params === []) {
            return $message;
        }
//        if (preg_match('~{\s*[\w.]+\s*,~u', $message)) {
//            $formatter = $this->getMessageFormatter();
//            $result    = $formatter->format($message, $params, $language);
//            if ($result === false) {
//                $errorMessage = $formatter->getErrorMessage();
//                Yii::warning("Formatting message for language '$language' failed with error: $errorMessage. The message being formatted was: $message.", __METHOD__);
//
//                return $message;
//            }
//
//            return $result;
//        }
        $p = [];
        foreach ($params as $name => $value) {
            $p['{' . $name . '}'] = $value;
        }
        return strtr($message, $p);
    }
    //
    private $_messageFormatter;
    public function getMessageFormatter() {
        if ($this->_messageFormatter === null) {
            $this->_messageFormatter = new MessageFormatter();
        }
        elseif (is_array($this->_messageFormatter) || is_string($this->_messageFormatter)) {
            $this->_messageFormatter = Yii::createObject($this->_messageFormatter);
        }
        return $this->_messageFormatter;
    }
    public function setMessageFormatter($value) {
        $this->_messageFormatter = $value;
    }
    //
}