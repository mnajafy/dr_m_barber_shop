<?php
namespace core\db;
use Framework;
use core\base\BaseObject;
class ActiveQuery extends BaseObject {
    /**
     * 
     */
    public $modelClass;
    public $select  = [];
    public $from    = [];
    public $where   = [];
    public $groupBy = [];
    public $orderBy = [];
    public $limit;
    public $offset;
    /**
     * @return ActiveQuery
     */
    public function select($columns = []) {
        $this->select = $columns;
        return $this;
    }
    /**
     * @return ActiveQuery
     */
    public function from($tables = []) {
        $this->from = $tables;
        return $this;
    }
    /**
     * @return ActiveQuery
     */
    public function where($conditions = []) {
        $this->where = $conditions;
        return $this;
    }
    /**
     * @return ActiveQuery
     */
    public function groupBy($columns = []) {
        $this->groupBy = $columns;
        return $this;
    }
    /**
     * @return ActiveQuery
     */
    public function orderBy($sort = []) {
        $this->orderBy = $sort;
        return $this;
    }
    /**
     * @return ActiveQuery
     */
    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }
    /**
     * @return ActiveQuery
     */
    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }
    /**
     * @return Command
     */
    public function createCommand() {
        $db      = Framework::$app->getDb();
        list($sql, $params) = $db->getSchema()->getQueryBuilder()->select($this);
        $command = $db->createCommand($sql, $params);
        return $command;
    }
    /**
     * 
     */
    public function populate($rows) {
        if (empty($rows)) {
            return [];
        }
        $models = [];
        foreach ($rows as $row) {
            $models[] = BaseObject::createObject(['class' => $this->modelClass])->populate($row);
        }
        return $models;
    }
    /**
     * @return ActiveRecord
     */
    public function one() {
        $row = $this->createCommand()->queryOne();
        if ($row === false) {
            return null;
        }
        $models = $this->populate([$row]);
        return reset($models) ?: null;
    }
    /**
     * @return ActiveRecord[]
     */
    public function all() {
        $rows = $this->createCommand()->queryAll();
        if ($rows === false) {
            return [];
        }
        return $this->populate($rows);
    }
    public function count($column = '*') {
        $this->select = ["COUNT($column)"];
        return $this->createCommand()->queryScalar();
    }
    /**
     * @param QueryBuilder $builder
     */
    public function prepare($builder) {
        if (empty($this->from)) {
            $this->from = [$this->getPrimaryTableName()];
        }
    }
    /**
     * @return string primary table name
     */
    protected function getPrimaryTableName() {
        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        return $modelClass::tableName();
    }
}