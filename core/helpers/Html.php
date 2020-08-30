<?php
namespace core\helpers;
use Framework;
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
                            $v    = implode(' ', $v);
                            $html .= " $name-$n=\"$v\"";
                        }
                        elseif (is_bool($v)) {
                            if ($v) {
                                $html .= " $name-$n";
                            }
                        }
                        else {
                            $html .= " $name-$n=\"$v\"";
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
    public static function tag($name, $content = '', $options = []) {
        if ($name === null || $name === false) {
            return $content;
        }
        $html = "<$name" . static::renderTagAttributes($options);
        return isset(static::$voidElements[$name]) ? $html . '/>' : "$html>$content</$name>";
    }
    //
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
    //
    public static function beginForm($action = '', $method = 'post', $options = []) {
        $options['action'] = $action;
        $options['method'] = $method;
        $form              = static::beginTag('form', $options);
        $request           = Framework::$app->getRequest();
        if ($request->enableCsrfValidation && strcasecmp($method, 'post') === 0) {
            $form .= static::input('hidden', ['name' => $request->csrfParam, 'value' => $request->getCsrfToken()]);
        }
        return $form;
    }
    public static function endForm() {
        return '</form>';
    }
    public static function submitButton($content = null, $options = []) {
        return static::tag('button', $content, $options);
    }
    //
    public static function input($type, $options = []) {
        if (!isset($options['type'])) {
            $options['type'] = $type;
        }
        return static::tag('input', '', $options);
    }
    public static function textarea($value, $options = []) {
        return static::tag('textarea', $value, $options);
    }
    public static function dropDownList($selection = null, $items = [], $options = []) {
        if (isset($options['multiple'])) {
            if (substr($options['name'], -2) !== '[]') {
                $options['name'] .= '[]';
            }
        }
        $lines = [];
        foreach ($items as $value => $content) {
            $selected = is_array($selection) ? in_array($value, $selection) : $value == $selection;
            $lines[]  = static::tag('option', $content, ['value' => $value, 'selected' => $selected]);
        }
        return static::tag('select', "\n" . implode("\n", $lines) . "\n", $options);
    }
    public static function checkboxList($selection = null, $items = [], $options = []) {
        if (substr($options['name'], -2) !== '[]') {
            $options['name'] .= '[]';
        }
        $lines = [];
        foreach ($items as $value => $content) {
            $selected = is_array($selection) ? in_array($value, $selection) : $value == $selection;
            $lines[]  = static::input('checkbox', $options);
        }
        return implode("\n", $lines);
    }
    public static function radioList($selection = null, $items = [], $options = []) {
        if (substr($options['name'], -2) !== '[]') {
            $options['name'] .= '[]';
        }
        $lines = [];
        foreach ($items as $value => $content) {
            $selected = is_array($selection) ? in_array($value, $selection) : $value == $selection;
            $lines[]  = static::input('checkbox', $options);
        }
        return implode("\n", $lines);
    }
    //
    public static function activeError($model, $attribute, $options = []) {
        $error = $model->getFirstError($attribute);
        $tag   = ArrayHelper::remove($options, 'tag', 'div');
        return static::tag($tag, $error, $options);
    }
    public static function activeLabel($model, $attribute, $options = []) {
        $content = isset($options['label']) ? ArrayHelper::remove($options, 'label') : $model->getAttributeLabel($attribute);
        return static::tag('label', $content, $options);
    }
    public static function validateActiveOptions($model, $attribute, &$options) {
        if (!isset($options['id'])) {
            $options['id'] = $attribute;
        }
        if (!isset($options['name'])) {
            $options['name'] = $attribute;
        }
        if (!isset($options['value'])) {
            $options['value'] = $model->$attribute;
        }
    }
    public static function activeInput($type, $model, $attribute, $options = []) {
        static::validateActiveOptions($model, $attribute, $options);
        return static::input($type, $options);
    }
    public static function activeTextarea($model, $attribute, $options = []) {
        static::validateActiveOptions($model, $attribute, $options);
        $value = ArrayHelper::remove($options, 'value');
        return static::textarea($value, $options);
    }
    public static function activeDropDownList($model, $attribute, $items = [], $options = []) {
        static::validateActiveOptions($model, $attribute, $options);
        $selection = ArrayHelper::remove($options, 'value');
        return static::dropDownList($selection, $items, $options);
    }
    public static function activeCheckboxList($model, $attribute, $items = [], $options = []) {
        static::validateActiveOptions($model, $attribute, $options);
        $selection = ArrayHelper::remove($options, 'value');
        return static::checkboxList($selection, $items, $options);
    }
    public static function activeRadioList($model, $attribute, $items = [], $options = []) {
        static::validateActiveOptions($model, $attribute, $options);
        $selection = ArrayHelper::remove($options, 'value');
        return static::radioList($selection, $items, $options);
    }
}