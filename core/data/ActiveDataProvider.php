<?php
namespace core\data;
use Exception;
use core\base\BaseObject;
use core\db\ActiveQuery;
/**
 *  ActiveDataProvider
 * 
 * @property \core\db\ActiveRecord[] $models
 * @property array $keys
 * @property int $totalCount
 * @property Pagination $pagination
 * @property Sort $sort
 * @property-read int $count
 */
class ActiveDataProvider extends BaseObject {
    public static $autoIdPrefix = 'cp-';
    public static $counter = 1;
    /**
     * @var ActiveQuery
     */
    public $query;
    public $id;
    public $key;
    private $_models;
    private $_keys;
    private $_totalCount;
    private $_pagination;
    private $_sort;
    public function __construct($config = array()) {
        if ($this->id === null) {
            $this->id = self::$autoIdPrefix . self::$counter;
            self::$counter++;
        }
        parent::__construct($config);
    }
    /**
     * @return \core\db\ActiveRecord[]
     */
    public function prepareModels() {
        if (!$this->query instanceof ActiveQuery) {
            throw new Exception('The "query" property must be an instance of a class that implements the ActiveQuery e.g. core\db\ActiveQuery or its subclasses.');
        }
        $query      = clone $this->query;
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();
            if ($pagination->totalCount === 0) {
                return [];
            }
            $query->limit($pagination->getLimit())->offset($pagination->getOffset());
        }
        if (($sort = $this->getSort()) !== false) {
            $query->orderBy($sort->getOrders());
        }
        return $query->all();
    }
    /**
     * @return array
     */
    public function prepareKeys($models) {
        $keys = [];
        if ($this->key !== null) {
            foreach ($models as $model) {
                if (is_string($this->key)) {
                    $keys[] = $model[$this->key];
                }
                else {
                    $keys[] = call_user_func($this->key, $model);
                }
            }
            return $keys;
        }
        elseif ($this->query instanceof ActiveQuery) {
            /* @var $class \core\db\ActiveRecord */
            $class = $this->query->modelClass;
            $pks   = $class::primaryKey();
            if (count($pks) === 1) {
                $pk = $pks[0];
                foreach ($models as $model) {
                    $keys[] = $model->$pk;
                }
            }
            else {
                foreach ($models as $model) {
                    $kk = [];
                    foreach ($pks as $pk) {
                        $kk[$pk] = $model->$pk;
                    }
                    $keys[] = $kk;
                }
            }
            return $keys;
        }
        return array_keys($models);
    }
    /**
     * @return int
     */
    public function prepareTotalCount() {
        if (!$this->query instanceof ActiveQuery) {
            throw new Exception('The "query" property must be an instance of a class that implements the ActiveQuery e.g. core\db\ActiveQuery or its subclasses.');
        }
        $query = clone $this->query;
        return (int) $query->limit(null)->offset(null)->orderBy([])->count();
    }
    /**
     * 
     */
    public function prepare($forcePrepare = false) {
        if ($forcePrepare || $this->_models === null) {
            $this->_models = $this->prepareModels();
        }
        if ($forcePrepare || $this->_keys === null) {
            $this->_keys = $this->prepareKeys($this->_models);
        }
    }
    /**
     * @return \core\db\ActiveRecord[]
     */
    public function getModels() {
        $this->prepare();
        return $this->_models;
    }
    public function setModels($models) {
        $this->_models = $models;
    }
    /**
     * @return array
     */
    public function getKeys() {
        $this->prepare();
        return $this->_keys;
    }
    public function setKeys($keys) {
        $this->_keys = $keys;
    }
    /**
     * @return int
     */
    public function getTotalCount() {
        if ($this->getPagination() === false) {
            return $this->getCount();
        }
        elseif ($this->_totalCount === null) {
            $this->_totalCount = $this->prepareTotalCount();
        }
        return $this->_totalCount;
    }
    public function setTotalCount($value) {
        $this->_totalCount = $value;
    }
    /**
     * @return Pagination|false
     */
    public function getPagination() {
        if ($this->_pagination === null) {
            $this->setPagination([]);
        }
        return $this->_pagination;
    }
    public function setPagination($value) {
        if (is_array($value)) {
            $config = ['class' => Pagination::class];
            if ($this->id !== null) {
                $config['pageParam']     = $this->id . '-page';
                $config['pageSizeParam'] = $this->id . '-per-page';
            }
            $this->_pagination = BaseObject::createObject(array_merge($config, $value));
        }
        elseif ($value instanceof Pagination || $value === false) {
            $this->_pagination = $value;
        }
        else {
            throw new Exception('Only Pagination instance, configuration array or false is allowed.');
        }
    }
    /**
     * @return Sort|false
     */
    public function getSort() {
        if ($this->_sort === null) {
            $this->setSort([]);
        }
        return $this->_sort;
    }
    public function setSort($value) {
        if (is_array($value)) {
            $config = ['class' => Sort::class];
            if ($this->id !== null) {
                $config['sortParam'] = $this->id . '-sort';
            }
            $this->_sort = BaseObject::createObject(array_merge($config, $value));
        }
        elseif ($value instanceof Sort || $value === false) {
            $this->_sort = $value;
        }
        else {
            throw new Exception('Only Sort instance, configuration array or false is allowed.');
        }
    }
    /**
     * @return int
     */
    public function getCount() {
        return count($this->getModels());
    }
}