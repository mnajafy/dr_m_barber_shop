<?php
namespace Core;
class Html {
    public static $voidElements = [
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
}