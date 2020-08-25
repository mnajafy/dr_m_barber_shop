<?php
namespace core\data;
use Framework;
use core\base\BaseObject;
/**
 * Pagination
 * 
 * @property-read int $limit
 * @property-read int $offset
 * @property-read int $pageCount
 * @property int $page
 * @property int $pageSize
 * 
 */
class Pagination extends BaseObject {
    //--------------------------------------------------------------------------
    public $pageParam       = 'page';
    public $pageSizeParam   = 'per-page';
    public $defaultPageSize = 10;
    public $totalCount      = 0;
    //--------------------------------------------------------------------------
    public function getLimit() {
        return $this->getPageSize();
    }
    public function getOffset() {
        return $this->getPage() * $this->getPageSize();
    }
    public function getPageCount() {
        $pageSize = $this->getPageSize();
        if ($pageSize < 1) {
            return $this->totalCount > 0 ? 1 : 0;
        }
        $totalCount = $this->totalCount < 0 ? 0 : (int) $this->totalCount;
        return (int) (($totalCount + $pageSize - 1) / $pageSize);
    }
    //--------------------------------------------------------------------------
    private $_page;
    public function getPage($recalculate = false) {
        if ($this->_page === null || $recalculate) {
            $page = (int) Framework::$app->getRequest()->get($this->pageParam, 1);
            $this->setPage($page - 1);
        }
        return $this->_page;
    }
    public function setPage($value) {
        $pageCount   = $this->getPageCount();
        $this->_page = ($value < 1 ? 0 : ($value >= $pageCount ? $pageCount - 1 : $value));
    }
    //--------------------------------------------------------------------------
    private $_pageSize;
    public function getPageSize() {
        if ($this->_pageSize === null) {
            $pageSize = (int) Framework::$app->getRequest()->get($this->pageSizeParam, $this->defaultPageSize);
            $this->setPageSize($pageSize);
        }
        return $this->_pageSize;
    }
    public function setPageSize($value) {
        $this->_pageSize = $value;
    }
    //--------------------------------------------------------------------------
    /**
     * @param int $page
     * @param int $pageSize
     * @return string Url
     */
    public function createUrl($page, $pageSize) {

        $params = Framework::$app->getRequest()->get();

        if ($page >= 0) {
            $params[$this->pageParam] = $page + 1;
        }
        else {
            unset($params[$this->pageParam]);
        }

        if ($pageSize <= 0) {
            $pageSize = $this->getPageSize();
        }

        if ($pageSize != $this->defaultPageSize) {
            $params[$this->pageSizeParam] = $pageSize;
        }
        else {
            unset($params[$this->pageSizeParam]);
        }

        $params[0]  = Framework::$app->controller->getRoute();
        $urlManager = Framework::$app->getUrlManager();
        return $urlManager->createUrl($params);
    }
}