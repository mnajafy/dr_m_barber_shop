<?php
namespace core\validators;
use core\base\BaseObject;
use Closure;
class Validator extends BaseObject {
    public $message;
    public $attributes               = [];
    public static $builtInValidators = [
        'required' => 'core\validators\RequiredValidator',
        'string'   => 'core\validators\StringValidator',
        'number'   => 'core\validators\NumberValidator',
        'integer'  => ['class' => 'core\validators\NumberValidator', 'integerOnly' => true],
        'email'    => 'core\validators\EmailValidator',
        'file'     => 'core\validators\FileValidator',
        'image'    => 'core\validators\ImageValidator',
        'boolean'  => 'core\validators\BooleanValidator',
        'compare'  => 'core\validators\CompareValidator',
        //
        'captcha'  => 'core\captcha\CaptchaValidator',
        'date'     => 'core\validators\DateValidator',
        'default'  => 'core\validators\DefaultValueValidator',
        'double'   => 'core\validators\NumberValidator',
        'each'     => 'core\validators\EachValidator',
        'exist'    => 'core\validators\ExistValidator',
        'filter'   => 'core\validators\FilterValidator',
        'in'       => 'core\validators\RangeValidator',
        'match'    => 'core\validators\RegularExpressionValidator',
        'safe'     => 'core\validators\SafeValidator',
        'unique'   => 'core\validators\UniqueValidator',
        'url'      => 'core\validators\UrlValidator',
        'ip'       => 'core\validators\IpValidator',
        'datetime' => ['class' => 'core\validators\DateValidator', /* 'type'  => DateValidator::TYPE_DATETIME, */],
        'time'     => ['class' => 'core\validators\DateValidator', /* 'type'  => DateValidator::TYPE_TIME */],
        'trim'     => ['class' => 'core\validators\FilterValidator', 'filter' => 'trim', 'skipOnArray' => true],
    ];
    public static function createValidator($type, $model, $attributes, $params = []) {
        $params['attributes'] = $attributes;
        if (is_array($type)) {
            $params = array_merge($type, $params);
        }
        elseif ($type instanceof Closure) {
            $params['class']  = __NAMESPACE__ . '\InlineValidator';
            $params['method'] = $type;
        }
        elseif (!isset(static::$builtInValidators[$type]) && method_exists($model, $type)) {
            $params['class']  = __NAMESPACE__ . '\InlineValidator';
            $params['method'] = [$model, $type];
        }
        else {
            if (isset(static::$builtInValidators[$type])) {
                $type = static::$builtInValidators[$type];
            }
            if (is_array($type)) {
                $params = array_merge($type, $params);
            }
            else {
                $params['class'] = $type;
            }
        }
        return BaseObject::createObject($params);
    }
    public function validateAttributes($model) {
        foreach ($this->attributes as $attribute) {
            $this->validateAttribute($model, $attribute);
        }
    }
    public function validateAttribute($model, $attribute) {
        $result = $this->validateValue($model->$attribute);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }
    public function validateValue($value) {
        
    }
    public function addError($model, $attribute, $message, $params = []) {
        $params['attribute'] = $model->getAttributeLabel($attribute);
        if (!isset($params['value'])) {
            $value = $model->$attribute;
            if (is_array($value)) {
                $params['value'] = 'array()';
            }
            elseif (is_object($value) && !method_exists($value, '__toString')) {
                $params['value'] = '(object)';
            }
            else {
                $params['value'] = $value;
            }
        }
        $model->addError($attribute, $this->formatMessage($message, $params));
    }
    public function formatMessage($message, $params) {
        foreach ($params as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        return $message;
        //return Framework::$app->getI18n()->format($message, $params, Yii::$app->language);
    }
    public function clientValidateAttribute($model, $attribute, $view) {
        return null;
    }
}