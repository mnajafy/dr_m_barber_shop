<?php
namespace core\validators;
class EmailValidator extends Validator {
    public $pattern     = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
    public $fullPattern = '/^[^@]*<[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/';
    public $allowName   = false;
    public function init() {
        parent::init();
        if ($this->message === null) {
            $this->message = '{attribute} is not a valid email address.';
        }
    }
    protected function validateValue($value) {
        if (!is_string($value)) {
            $valid = false;
        }
        elseif (!preg_match('/^(?P<name>(?:"?([^"]*)"?\s)?)(?:\s+)?(?:(?P<open><?)((?P<local>.+)@(?P<domain>[^>]+))(?P<close>>?))$/i', $value, $matches)) {
            $valid = false;
        }
        else {
            if (strlen($matches['local']) > 64) {
                $valid = false;
            }
            elseif (strlen($matches['local'] . '@' . $matches['domain']) > 254) {
                $valid = false;
            }
            else {
                $valid = preg_match($this->pattern, $value) || ($this->allowName && preg_match($this->fullPattern, $value));
            }
        }

        return $valid ? null : [$this->message, []];
    }
    public function clientValidateAttribute($model, $attribute, $view) {
        ValidationAsset::register($view);
        $options = $this->getClientOptions($model, $attribute);
        return 'framework.validation.email(value, messages, ' . Json::htmlEncode($options) . ');';
    }
    public function getClientOptions($model, $attribute) {
        $options = [
//            'pattern'     => new JsExpression($this->pattern),
//            'fullPattern' => new JsExpression($this->fullPattern),
            'allowName'   => $this->allowName,
            'message'     => $this->formatMessage($this->message, [
                'attribute' => $model->getAttributeLabel($attribute),
            ]),
        ];
        return $options;
    }
}