<?php
namespace core\helpers;
use Exception;
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
                $value = $value ? 'true' : 'false';
            }
            if (is_array($value)) {
                $value = implode(' ', $value);
            }
            $html .= " $name='$value'";
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
}