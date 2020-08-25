<?php
namespace core\grid;
class SerialColumn extends Column {
    public $header = '#';
    public function renderDataCellContent($model, $key, $index) {
        $pagination = $this->grid->dataProvider->getPagination();
        if ($pagination !== false) {
            return $pagination->getOffset() + $index + 1;
        }
        return $index + 1;
    }
}