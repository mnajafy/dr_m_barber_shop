<?php
namespace core\grid;
use core\base\Model;
use core\db\ActiveQuery;
use core\helpers\ArrayHelper;
use core\data\ActiveDataProvider;
class DataColumn extends Column {
    public $sortLinkOptions = [];
    public $enableSorting = true;
    public $attribute;
    public $label;
    public $value;
    public $format = 'text';
    public function renderHeaderCellContent() {
        if ($this->header !== null || $this->label === null && $this->attribute === null) {
            return parent::renderHeaderCellContent();
        }

        $label = $this->getHeaderCellLabel();
        
        if (
                $this->attribute !== null &&
                $this->enableSorting &&
                ($sort = $this->grid->dataProvider->getSort()) !== false
        ) {
            return $sort->link($this->attribute, array_merge($this->sortLinkOptions, ['label' => $label]));
        }

        return $label;
    }
    public function getHeaderCellLabel() {

        if ($this->label !== null) {
            return $this->label;
        }

        $provider = $this->grid->dataProvider;
        if ($provider instanceof ActiveDataProvider && $provider->query instanceof ActiveQuery) {
            /* @var $modelClass Model */
            $modelClass = $provider->query->modelClass;
            $model      = new $modelClass();
            return $model->getAttributeLabel($this->attribute);
        }

        $models = $provider->getModels();
        if (($model  = reset($models)) instanceof Model) {
            /* @var $model Model */
            return $model->getAttributeLabel($this->attribute);
        }

        return $this->attribute;
        //return Inflector::camel2words($this->attribute);
    }
    public function getDataCellValue($model, $key, $index) {
        if ($this->value !== null) {
            if (is_string($this->value)) {
                return ArrayHelper::getValue($model, $this->value);
            }
            return call_user_func($this->value, $model, $key, $index, $this);
        }

        if ($this->attribute !== null) {
            return ArrayHelper::getValue($model, $this->attribute);
        }

        return null;
    }
    public function renderDataCellContent($model, $key, $index) {
        if ($this->content === null) {
            return $this->getDataCellValue($model, $key, $index);
            //return $this->grid->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
        }
        return parent::renderDataCellContent($model, $key, $index);
    }
}