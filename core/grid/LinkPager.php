<?php
namespace core\grid;
use Exception;
use core\helpers\Html;
use core\helpers\ArrayHelper;
use core\base\Widget;
class LinkPager extends Widget {
    /**
     * @var \core\data\Pagination
     */
    public $pagination;
    public $options                       = ['class' => 'pagination'];
    public $linkContainerOptions          = [];
    public $linkOptions                   = [];
    public $pageCssClass;
    public $firstPageCssClass             = 'first';
    public $lastPageCssClass              = 'last';
    public $prevPageCssClass              = 'prev';
    public $nextPageCssClass              = 'next';
    public $activePageCssClass            = 'active';
    public $disabledPageCssClass          = 'disabled';
    public $disabledListItemSubTagOptions = [];
    public $maxButtonCount                = 10;
    public $nextPageLabel                 = '&raquo;';
    public $prevPageLabel                 = '&laquo;';
    public $firstPageLabel                = 'first';
    public $lastPageLabel                 = 'last';
    public $hideOnSinglePage              = true;
    public $disableCurrentPageButton      = false;
    public function init() {
        parent::init();
        if ($this->pagination === null) {
            throw new Exception('The "pagination" property must be set.');
        }
    }
    public function run() {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }
        $buttons     = [];
        $currentPage = $this->pagination->getPage();

        // first page
        $firstPageLabel = $this->firstPageLabel === true ? 'first' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, $this->disableCurrentPageButton && $i == $currentPage, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        $options = $this->options;
        $tag     = ArrayHelper::remove($options, 'tag', 'ul');
        return Html::tag($tag, implode("\n", $buttons), $options);
    }
    protected function renderPageButton($label, $page, $class, $disabled, $active) {
        $options     = $this->linkContainerOptions;
        $linkWrapTag = ArrayHelper::remove($options, 'tag', 'li');
        Html::addCssClass($options, empty($class) ? $this->pageCssClass : $class);

        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);
            $disabledItemOptions = $this->disabledListItemSubTagOptions;
            $tag                 = ArrayHelper::remove($disabledItemOptions, 'tag', 'a');
            return Html::tag($linkWrapTag, Html::tag($tag, $label, $disabledItemOptions), $options);
        }
        $linkOptions              = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        return Html::tag($linkWrapTag, Html::a($label, $this->pagination->createUrl($page, 0), $linkOptions), $options);
    }
    protected function getPageRange() {
        $currentPage = $this->pagination->getPage();
        $pageCount   = $this->pagination->getPageCount();
        $beginPage   = max(0, $currentPage - (int) ($this->maxButtonCount / 2));
        if (($endPage     = $beginPage + $this->maxButtonCount - 1) >= $pageCount) {
            $endPage   = $pageCount - 1;
            $beginPage = max(0, $endPage - $this->maxButtonCount + 1);
        }
        return [$beginPage, $endPage];
    }
}