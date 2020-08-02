<?php
namespace Core;
use Exception;
class Widget extends BaseObject {
    public static $counter      = 0;
    public static $autoIdPrefix = 'w';
    public static $stack        = [];
    public function init() {
        parent::init();
    }
    public static function begin($config = []) {
        $config['class'] = get_called_class();
        /* @var $widget Widget */
        $widget          = BaseObject::createObject($config);
        self::$stack[]   = $widget;
        return $widget;
    }
    public static function end() {
        if (empty(self::$stack)) {
            throw new Exception('Unexpected ' . get_called_class() . '::end() call. A matching begin() is not found.');
        }

        $widget = array_pop(self::$stack);
        if (get_class($widget) !== get_called_class()) {
            throw new Exception('Expecting end() of ' . get_class($widget) . ', found ' . get_called_class());
        }

        /* @var $widget Widget */
        $result = $widget->run();
        echo $result;

        return $widget;
    }
    public static function widget($config = []) {
        ob_start();
        ob_implicit_flush(false);
        try {
            /* @var $widget Widget */
            $config['class'] = get_called_class();
            $widget          = BaseObject::createObject($config);
            $result          = $widget->run();
        }
        catch (Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }
        return ob_get_clean() . $result;
    }
    public function run() {
        
    }
    private $_id;
    public function getId() {
        if ($this->_id === null) {
            $this->_id = static::$autoIdPrefix . static::$counter++;
        }
        return $this->_id;
    }
}