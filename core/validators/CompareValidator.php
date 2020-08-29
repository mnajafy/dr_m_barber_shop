<?php
namespace core\validators;
use Exception;
use core\helpers\Html;
class CompareValidator extends Validator {
    const TYPE_STRING      = 'string';
    const TYPE_NUMBER      = 'number';
    public $compareAttribute;
    public $compareValue;
    public $type     = self::TYPE_STRING;
    public $operator = '==';
    public function init() {
        parent::init();
        if ($this->message === null) {
            switch ($this->operator) {
                case '==':
                    $this->message = '{attribute} must be equal to "{compareValueOrAttribute}".';
                    break;
                case '===':
                    $this->message = '{attribute} must be equal to "{compareValueOrAttribute}".';
                    break;
                case '!=':
                    $this->message = '{attribute} must not be equal to "{compareValueOrAttribute}".';
                    break;
                case '!==':
                    $this->message = '{attribute} must not be equal to "{compareValueOrAttribute}".';
                    break;
                case '>':
                    $this->message = '{attribute} must be greater than "{compareValueOrAttribute}".';
                    break;
                case '>=':
                    $this->message = '{attribute} must be greater than or equal to "{compareValueOrAttribute}".';
                    break;
                case '<':
                    $this->message = '{attribute} must be less than "{compareValueOrAttribute}".';
                    break;
                case '<=':
                    $this->message = '{attribute} must be less than or equal to "{compareValueOrAttribute}".';
                    break;
                default:
                    throw new Exception("Unknown operator: {$this->operator}");
            }
        }
    }
    public function validateAttribute($model, $attribute) {
        $value = $model->$attribute;
        if (is_array($value)) {
            $this->addError($model, $attribute, '{attribute} is invalid.');
            return;
        }
        if ($this->compareValue !== null) {
            $compareLabel            = $compareValue            = $compareValueOrAttribute = $this->compareValue;
        }
        else {
            $compareAttribute        = $this->compareAttribute === null ? $attribute . '_repeat' : $this->compareAttribute;
            $compareValue            = $model->$compareAttribute;
            $compareLabel            = $compareValueOrAttribute = $model->getAttributeLabel($compareAttribute);
        }
        if ($this->compareValues($this->operator, $this->type, $value, $compareValue)) {
            return [];
        }
        $this->addError($model, $attribute, $this->message, ['compareAttribute' => $compareLabel, 'compareValue' => $compareValue, 'compareValueOrAttribute' => $compareValueOrAttribute]);
    }
    public function compareValues($operator, $type, $value, $compareValue) {
        if ($type === self::TYPE_NUMBER) {
            $value        = (float) $value;
            $compareValue = (float) $compareValue;
        }
        else {
            $value        = (string) $value;
            $compareValue = (string) $compareValue;
        }
        switch ($operator) {
            case '==':
                return $value == $compareValue;
            case '===':
                return $value === $compareValue;
            case '!=':
                return $value != $compareValue;
            case '!==':
                return $value !== $compareValue;
            case '>':
                return $value > $compareValue;
            case '>=':
                return $value >= $compareValue;
            case '<':
                return $value < $compareValue;
            case '<=':
                return $value <= $compareValue;
            default:
                return false;
        }
    }
    public function clientValidateAttribute($model, $attribute, $view) {
        ValidationAsset::register($view);
        $options = $this->getClientOptions($model, $attribute);
        return 'framework.validation.compare(value, messages, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ', $form);';
    }
    public function getClientOptions($model, $attribute) {
        $options = ['operator' => $this->operator, 'type' => $this->type];
        if ($this->compareValue !== null) {
            $options['compareValue'] = $this->compareValue;
            $compareLabel            = $compareValue            = $compareValueOrAttribute = $this->compareValue;
        }
        else {
            $compareAttribute                = $this->compareAttribute === null ? $attribute . '_repeat' : $this->compareAttribute;
            $compareValue                    = $model->getAttributeLabel($compareAttribute);
//            $options['compareAttribute']     = Html::getInputId($compareAttribute);
//            $options['compareAttributeName'] = Html::getInputName($compareAttribute);
            $compareLabel                    = $compareValueOrAttribute         = $model->getAttributeLabel($compareAttribute);
        }
        $options['message'] = $this->formatMessage($this->message, [
            'attribute'               => $model->getAttributeLabel($attribute),
            'compareAttribute'        => $compareLabel,
            'compareValue'            => $compareValue,
            'compareValueOrAttribute' => $compareValueOrAttribute,
        ]);

        return $options;
    }
}