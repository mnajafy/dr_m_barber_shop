<?php
namespace core\helpers;
use Exception;
use core\helpers\Url;
class Html {
    public static $attributeRegex = '/(^|.*\])([\w\.\+]+)(\[.*|$)/u';
    public static $voidElements   = [
        'area'    => 1,
        'base'    => 1,
        'br'      => 1,
        'col'     => 1,
        'command' => 1,
        'embed'   => 1,
        'hr'      => 1,
        'img'     => 1,
        'input'   => 1,
        'keygen'  => 1,
        'link'    => 1,
        'meta'    => 1,
        'param'   => 1,
        'source'  => 1,
        'track'   => 1,
        'wbr'     => 1,
    ];
    public static function renderTagAttributes($attributes = []) {
        $html = '';
        foreach ($attributes as $name => $value) {
            if ($value === NULL) {
                continue;
            }
            if (is_bool($value)) {
                if ($value) {
                    $html .= " $name";
                }
            }
            else if (is_array($value)) {
                if (empty($value)) {
                    continue;
                }
                if ($name === 'data') {
                    foreach ($value as $n => $v) {
                        if (is_array($v)) {
                            $v = implode(' ', $v);
                            $html  .= " $name-$n=\"$v\"";
                        }
                        elseif (is_bool($v)) {
                            if ($v) {
                                $html .= " $name-$n";
                            }
                        }
                        else {
                            $html  .= " $name-$n=\"$v\"";
                        }
                    }
                }
                else {
                    $value = implode(' ', $value);
                    $html  .= " $name=\"$value\"";
                }
            }
            else {
                $html .= " $name=\"$value\"";
            }
        }
        return $html;
    }
    public static function beginTag($name, $options = []) {
        if ($name === null || $name === false) {
            return '';
        }
        return "<$name" . static::renderTagAttributes($options) . '>';
    }
    public static function endTag($name) {
        if ($name === null || $name === false) {
            return '';
        }
        return "</$name>";
    }
    public static function beginForm($action = '', $method = 'post', $options = []) {
        $options['action'] = $action;
        $options['method'] = $method;
        $form              = static::beginTag('form', $options);
        // hidden inputs
        return $form;
    }
    public static function endForm() {
        return '</form>';
    }
    public static function tag($name, $content = '', $options = []) {
        if ($name === null || $name === false) {
            return $content;
        }
        $html = "<$name" . static::renderTagAttributes($options);
        return isset(static::$voidElements[$name]) ? $html . '/>' : "$html>$content</$name>";
    }
    public static function activeLabel($model, $attribute, $options = []) {
        $content = null;
        if (isset($options['label'])) {
            $content = $options['label'];
            unset($options['label']);
        }
        else {
            $content = $model->getAttributeLabel($attribute);
        }
        return static::tag('label', $content, $options);
    }
    public static function activeTextInput($model, $attribute, $options = []) {
        return static::activeInput('text', $model, $attribute, $options);
    }
    public static function activeInput($type, $model, $attribute, $options = []) {
        $name  = isset($options['name']) ? $options['name'] : $attribute;
        $value = isset($options['value']) ? $options['value'] : $model->$attribute;
        return static::input($type, $name, $value, $options);
    }
    public static function input($type, $name = null, $value = null, $options = []) {
        if (!isset($options['type'])) {
            $options['type'] = $type;
        }
        $options['name']  = $name;
        $options['value'] = $value === null ? null : (string) $value;
        return static::tag('input', '', $options);
    }
    public static function submitButton($content = null, $options = []) {
        return static::tag('button', $content, $options);
    }
    public static function getAttributeName($attribute) {
        if (preg_match(static::$attributeRegex, $attribute, $matches)) {
            return $matches[2];
        }
        throw new Exception('Attribute name must contain word characters only.');
    }
    public static function getInputName($attribute) {
        $matches = [];
        if (!preg_match(static::$attributeRegex, $attribute, $matches)) {
            throw new Exception('Attribute name must contain word characters only.');
        }
        $_prefix    = $matches[1];
        $_attribute = $matches[2];
        $_suffix    = $matches[3];
        return $_prefix . "[$_attribute]" . $_suffix;
    }
    public static function getInputId($attribute) {
        $name = mb_strtolower(static::getInputName($attribute), 'UTF-8');
        return str_replace(['[]', '][', '[', ']', ' ', '.'], ['', '-', '-', '', '-', '-'], $name);
    }
    public static function error($model, $attribute, $options = []) {
        $error = $model->getFirstError($attribute);
        $tag   = ArrayHelper::remove($options, 'tag', 'div');
        return static::tag($tag, $error, $options);
    }
    public static function a($text, $url = null, $options = []) {
        if ($url !== null) {
            $options['href'] = Url::to($url);
        }
        return static::tag('a', $text, $options);
    }
    public static function mergeCssClasses(array $existingClasses, array $additionalClasses) {
        foreach ($additionalClasses as $key => $class) {
            if (is_int($key) && !in_array($class, $existingClasses)) {
                $existingClasses[] = $class;
            }
            elseif (!isset($existingClasses[$key])) {
                $existingClasses[$key] = $class;
            }
        }
        return array_unique($existingClasses);
    }
    public static function addCssClass(&$options, $class) {
        if (isset($options['class'])) {
            if (is_array($options['class'])) {
                $options['class'] = self::mergeCssClasses($options['class'], (array) $class);
            }
            else {
                $classes          = preg_split('/\s+/', $options['class'], -1, PREG_SPLIT_NO_EMPTY);
                $options['class'] = implode(' ', self::mergeCssClasses($classes, (array) $class));
            }
        }
        else {
            $options['class'] = $class;
        }
    }
}