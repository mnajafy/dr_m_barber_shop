<?php
namespace core\grid;
use Exception;
use core\base\Widget;
use core\helpers\Html;
use core\base\BaseObject;
use core\helpers\ArrayHelper;
class GridView extends Widget {
    //--------------------------------------------------------------------------
    /**
     * @var \core\data\ActiveDataProvider
     */
    public $dataProvider;
    public $emptyText;
    public $layout           = "{items}\n{pager}";
    public $columns          = [];
    public $options          = [];
    public $emptyTextOptions = [];
    public $pager            = [];
    public $headerRowOptions = [];
    public $rowOptions       = [];
    public $tableOptions     = [];
    //--------------------------------------------------------------------------
    public function init() {
        parent::init();
        if ($this->dataProvider === null) {
            throw new Exception('The "dataProvider" property must be set.');
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        $this->initColumns();
    }
    public function initColumns() {
        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            }
            else {
                $config = array_merge(['class' => DataColumn::class, 'grid' => $this], $column);
                $column = BaseObject::createObject($config);
            }
            $this->columns[$i] = $column;
        }
    }
    public function createDataColumn($text) {
        $matches = [];
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new Exception('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }
        return BaseObject::createObject([
                    'class'     => DataColumn::class,
                    'grid'      => $this,
                    'attribute' => $matches[1],
                    'format'    => isset($matches[3]) ? $matches[3] : 'text',
                    'label'     => isset($matches[5]) ? $matches[5] : null,
        ]);
    }
    //--------------------------------------------------------------------------
    public function run() {
        $content = $this->renderEmpty();
        if ($this->dataProvider->getCount() > 0) {
            $content = preg_replace_callback('/{\\w+}/', function ($matches) {
                $content = $this->renderSection($matches[0]);
                return $content === false ? $matches[0] : $content;
            }, $this->layout);
        }
        $options = $this->options;
        $tag     = ArrayHelper::remove($options, 'tag', 'div');
        return Html::tag($tag, $content, $options);
    }
    public function renderEmpty() {
        if ($this->emptyText === false) {
            return '';
        }
        $options = $this->emptyTextOptions;
        $tag     = ArrayHelper::remove($options, 'tag', 'div');
        return Html::tag($tag, $this->emptyText, $options);
    }
    public function renderSection($name) {
        switch ($name) {
            case '{summary}':
                return $this->renderSummary();
            case '{items}':
                return $this->renderItems();
            case '{pager}':
                return $this->renderPager();
            case '{sorter}':
                return $this->renderSorter();
            case '{errors}':
                return $this->renderErrors();
            default:
                return false;
        }
    }
    public function renderSummary() {
        
    }
    public function renderItems() {
        $header  = $this->renderTableHeader();
        $body    = $this->renderTableBody();
        $content = array_filter([
            $header,
            $body,
        ]);
        return Html::tag('table', implode("\n", $content), $this->tableOptions);
    }
    public function renderPager() {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class LinkPager */
        $pager = ArrayHelper::merge(['pagination' => $pagination], $this->pager);
        $class = ArrayHelper::remove($pager, 'class', LinkPager::class);
        return $class::widget($pager);
    }
    public function renderSorter() {
        
    }
    public function renderErrors() {
        
    }
    //--------------------------------------------------------------------------
    public function renderTableHeader() {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        return "<thead>\n" . $content . "\n</thead>";
    }
    public function renderTableBody() {
        $models = array_values($this->dataProvider->getModels());
        $keys   = $this->dataProvider->getKeys();
        $rows   = [];
        foreach ($models as $index => $model) {
            $rows[] = $this->renderTableRow($model, $keys[$index], $index);
        }
        if (empty($rows) && $this->emptyText !== false) {
            $colspan = count($this->columns);
            return "<tbody>\n<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>\n</tbody>";
        }
        return "<tbody>\n" . implode("\n", $rows) . "\n</tbody>";
    }
    public function renderTableRow($model, $key, $rowIndex) {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderDataCell($model, $key, $rowIndex);
        }
        if ($this->rowOptions instanceof Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $rowIndex, $this);
        }
        else {
            $options = $this->rowOptions;
        }
        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;
        return Html::tag('tr', implode('', $cells), $options);
    }
}