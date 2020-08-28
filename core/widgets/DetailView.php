<?php
namespace core\widgets;
use Closure;
use Exception;
use core\base\Model;
use core\base\Widget;
use core\helpers\Html;
use core\helpers\ArrayHelper;
class DetailView extends Widget {
    public $model;
    public $attributes;
    public $options  = [];
    public $tableOptions  = [];
    public $template = '<tr><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>';
    public function init() {
        parent::init();
        if ($this->model === null) {
            throw new Exception('Please specify the "model" property.');
        }
        $this->normalizeAttributes();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }
    public function run() {
        $options = $this->options;
        $tag     = ArrayHelper::remove($options, 'tag', 'div');
        $content = $this->renderTable();
        return Html::tag($tag, $content, $options);
    }
    protected function renderTable() {
        $rows = [];
        $i    = 0;
        foreach ($this->attributes as $attribute) {
            $rows[] = $this->renderAttribute($attribute, $i++);
        }
        $options = $this->tableOptions;
        $tag     = ArrayHelper::remove($options, 'tag', 'table');
        return Html::tag($tag, implode("\n", $rows), $options);
    }
    protected function renderAttribute($attribute, $index) {
        if (is_string($this->template)) {
            $captionOptions = Html::renderTagAttributes(ArrayHelper::getValue($attribute, 'captionOptions', []));
            $contentOptions = Html::renderTagAttributes(ArrayHelper::getValue($attribute, 'contentOptions', []));
            return strtr($this->template, [
                //'{value}'          => $this->formatter->format($attribute['value'], $attribute['format']),
                '{label}'          => $attribute['label'],
                '{value}'          => $attribute['value'],
                '{captionOptions}' => $captionOptions,
                '{contentOptions}' => $contentOptions,
            ]);
        }
        return call_user_func($this->template, $attribute, $index, $this);
    }
    protected function normalizeAttributes() {
        if ($this->attributes === null) {
            if ($this->model instanceof Model) {
                $this->attributes = $this->model->attributes();
            }
            else {
                throw new Exception('The "model" property must be an object.');
            }
        }
        foreach ($this->attributes as $i => $attribute) {
            if (is_string($attribute)) {
                if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $attribute, $matches)) {
                    throw new Exception('The attribute must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
                }
                $attribute = [
                    'attribute' => $matches[1],
                    'format'    => isset($matches[3]) ? $matches[3] : 'text',
                    'label'     => isset($matches[5]) ? $matches[5] : null,
                ];
            }
            if (!is_array($attribute)) {
                throw new Exception('The attribute configuration must be an array.');
            }
            if (isset($attribute['visible']) && !$attribute['visible']) {
                unset($this->attributes[$i]);
                continue;
            }
            if (!isset($attribute['format'])) {
                $attribute['format'] = 'text';
            }
            if (isset($attribute['attribute'])) {
                $attributeName = $attribute['attribute'];
                if (!isset($attribute['label'])) {
                    $attribute['label'] = $this->model instanceof Model ? $this->model->getAttributeLabel($attributeName) : $attributeName;
                }
                if (!array_key_exists('value', $attribute)) {
                    $attribute['value'] = ArrayHelper::getValue($this->model, $attributeName);
                }
            }
            elseif (!isset($attribute['label']) || !array_key_exists('value', $attribute)) {
                throw new Exception('The attribute configuration requires the "attribute" element to determine the value and display label.');
            }
            if ($attribute['value'] instanceof Closure) {
                $attribute['value'] = call_user_func($attribute['value'], $this->model, $this);
            }
            $this->attributes[$i] = $attribute;
        }
    }
}