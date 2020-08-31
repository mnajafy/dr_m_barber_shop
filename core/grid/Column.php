<?php
namespace core\grid;
use Closure;
use core\helpers\Html;
use core\base\BaseObject;
class Column extends BaseObject {
    /**
     * @var GridView
     */
    public $grid;
    public $header;
    public $content;
    public $footer;
    public $options        = [];
    public $headerOptions  = [];
    public $contentOptions = [];
    public $footerOptions  = [];
    public function renderHeaderCell() {
        return Html::tag('th', $this->renderHeaderCellContent(), $this->headerOptions);
    }
    public function renderHeaderCellContent() {
        return trim($this->header) !== '' ? $this->header : $this->getHeaderCellLabel();
    }
    public function getHeaderCellLabel() {
        return $this->grid->emptyCell;
    }
    public function renderDataCell($model, $key, $index) {
        if ($this->contentOptions instanceof Closure) {
            $options = call_user_func($this->contentOptions, $model, $key, $index, $this);
        }
        else {
            $options = $this->contentOptions;
        }
        return Html::tag('td', $this->renderDataCellContent($model, $key, $index), $options);
    }
    public function renderDataCellContent($model, $key, $index) {
        if ($this->content !== null) {
            return call_user_func($this->content, $model, $key, $index, $this);
        }
        return $this->grid->emptyCell;
    }
    public function renderFooterCell() {
        return Html::tag('td', $this->renderFooterCellContent(), $this->footerOptions);
    }
    public function renderFooterCellContent() {
        return trim($this->footer) !== '' ? $this->footer : $this->grid->emptyCell;
    }
}