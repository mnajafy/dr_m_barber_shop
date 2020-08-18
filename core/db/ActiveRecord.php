<?php
namespace core\db;
use PDO;
use Exception;
use Framework;
use core\base\Model;
use core\base\BaseObject;
use core\helpers\ArrayHelper;
class ActiveRecord extends Model {
    private $_oldItems;
    private $_items = [];
    //--------------------------------------------------------------------------
    public static function one($id) {
        $prepare = Framework::$app->db->pdo->prepare('SELECT * FROM ' . static::tablename() . ' WHERE id = ?');
        $prepare->execute([$id]);
        $row     = $prepare->fetch(PDO::FETCH_ASSOC);
        $model   = new static();
        return $row ? $model->populate($row) : null;
    }
    public static function all() {
        $prepare = Framework::$app->db->pdo->prepare('SELECT * FROM ' . static::tablename());
        $prepare->execute();
        $rows    = $prepare->fetchAll(PDO::FETCH_ASSOC);
        $models  = [];
        foreach ($rows as $row) {
            $model    = new static();
            $models[] = $model->populate($row);
        }
        return $models;
    }
    public static function runOne($sql, $params = []) {
        $prepare = Framework::$app->db->pdo->prepare($sql);
        $prepare->execute($params);
        $row     = $prepare->fetch(PDO::FETCH_ASSOC);
        $model   = new static();
        return $row ? $model->populate($row) : null;
    }
    public static function runAll($sql, $params = []) {
        $prepare = Framework::$app->db->pdo->prepare($sql);
        $prepare->execute($params);
        $rows    = $prepare->fetchAll(PDO::FETCH_ASSOC);
        $models  = [];
        foreach ($rows as $row) {
            $model    = new static();
            $models[] = $model->populate($row);
        }
        return $models;
    }
    //--------------------------------------------------------------------------
    public function __get($name) {
        if (isset($this->_items[$name]) || array_key_exists($name, $this->_items)) {
            return $this->_items[$name];
        }

        if ($this->hasAttribute($name)) {
            return null;
        }

        return parent::__get($name);
    }
    public function __set($name, $value) {
        if ($this->hasAttribute($name)) {
            $this->_items[$name] = $value;
        }
        else {
            parent::__set($name, $value);
        }
    }
    public function __isset($name) {
        return $this->__get($name) !== null;
    }
    public function __unset($name) {
        if ($this->hasAttribute($name)) {
            unset($this->_attributes[$name]);
        }
        else {
            parent::__unset($name);
        }
    }
    public function hasAttribute($name) {
        return isset($this->_items[$name]) || in_array($name, $this->attributes(), true);
    }
    //--------------------------------------------------------------------------
    /**
     * @return string Table Name
     */
    public static function tablename() {
        return strtolower(basename(get_called_class()));
    }
    /**
     * @return Database
     */
    public static function getDb() {
        return Framework::$app->getDb();
    }
    /**
     * @return Schema
     */
    public static function getSchema() {
        return static::getDb()->getSchema();
    }
    /**
     * @return db\TableSchema
     */
    public static function getTableSchema() {
        $tableSchema = static::getSchema()->getTableSchema(static::tablename());
        if ($tableSchema === null) {
            throw new Exception('The table does not exist: ' . static::tablename());
        }
        return $tableSchema;
    }
    /**
     * 
     */
    public static function primaryKey() {
        return static::getTableSchema()->primaryKey;
    }
    /**
     * @return ActiveQuery
     */
    public static function find() {
        return BaseObject::createObject(['class' => ActiveQuery::class, 'modelClass' => get_called_class()]);
    }
    /**
     * @return ActiveRecord
     */
    public static function findOne($condition) {
        return static::findByCondition($condition)->one();
    }
    /**
     * @return ActiveRecord
     */
    public static function findAll($condition) {
        return static::findByCondition($condition)->all();
    }
    /**
     * @return ActiveQuery
     */
    protected static function findByCondition($condition) {
        $query = static::find();
        if (!ArrayHelper::isAssociative($condition)) {
            $primaryKey = static::primaryKey();
            if (!isset($primaryKey[0])) {
                throw new Exception('"' . get_called_class() . '" must have a primary key.');
            }
            $condition = [$primaryKey[0] => is_array($condition) ? array_values($condition) : $condition];
        }
        return $query->where($condition);
    }
    //--------------------------------------------------------------------------
    /**
     * @return array Attributes
     */
    public function attributes() {
        return array_keys(static::getTableSchema()->columns);
    }
    /**
     * @param array $row
     * @return ActiveRecord
     */
    public function populate($row) {
        $columns = static::getTableSchema()->columns;
        foreach ($row as $name => $value) {
            if (isset($columns[$name])) {
                $this->_items[$name] = $columns[$name]->phpTypecast($value);
            }
        }
        $this->_oldItems = $this->_items;
        return $this;
    }
    /**
     * @return bool
     */
    public function getIsNewRecord() {
        return $this->_oldItems === null;
    }
    /**
     * @return bool
     */
    public function save($runValidation = true) {
        if ($runValidation && !$this->validate()) {
            return false;
        }
        return ($this->getIsNewRecord() ? $this->insert() : $this->update());
    }
    /**
     * @return bool
     */
    public function update() {

        $columns = $this->getDirtyAttributes();
        if (empty($columns)) {
            return true;
        }

        $table     = static::tablename();
        $condition = $this->getOldPrimaryKey();

        $params  = [];
        $sql     = static::getSchema()->getQueryBuilder()->update($table, $columns, $condition, $params);
        $command = static::getDb()->createCommand($sql, $params);
        $result  = $command->execute();

        if ($result) {
            foreach ($columns as $name => $value) {
                $this->_oldItems[$name] = $value;
            }
        }

        return $result;
    }
    /**
     * @return bool
     */
    public function insert() {

        $table   = static::tablename();
        $columns = $this->getDirtyAttributes();

        $params  = [];
        $sql     = static::getSchema()->getQueryBuilder()->insert($table, $columns, $params);
        $command = static::getDb()->createCommand($sql, $params);
        $result  = $command->execute();

        if ($result) {
            $ts  = static::getTableSchema();
            $pks = $ts->primaryKey;
            foreach ($pks as $key) {
                if ($ts->columns[$key]->autoIncrement) {
                    $value              = static::getDb()->pdo->lastInsertId();
                    $id                 = $ts->columns[$key]->phpTypecast($value);
                    $columns[$key]      = $id;
                    $this->_items[$key] = $id;
                    break;
                }
            }
            $this->_oldItems = $columns;
        }

        return $result;
    }
    /**
     * @return bool
     */
    public function delete() {

        $table     = static::tablename();
        $condition = $this->getOldPrimaryKey();

        $params  = [];
        $sql     = static::getSchema()->getQueryBuilder()->delete($table, $condition, $params);
        $command = static::getDb()->createCommand($sql, $params);
        $result  = $command->execute();

        if ($result) {
            $this->_oldItems = null;
        }

        return $result;
    }
    /**
     * @return bool
     */
    public function getOldPrimaryKey() {
        $keys   = static::primaryKey();
        $values = [];
        foreach ($keys as $name) {
            $values[$name] = isset($this->_oldItems[$name]) ? $this->_oldItems[$name] : null;
        }
        return $values;
    }
    /**
     * 
     */
    public function getDirtyAttributes() {
        $names      = array_flip($this->attributes());
        $attributes = [];
        if ($this->_oldItems === null) {
            foreach ($this->_items as $name => $value) {
                if (isset($names[$name])) {
                    $attributes[$name] = $value;
                }
            }
        }
        else {
            foreach ($this->_items as $name => $value) {
                if (isset($names[$name]) && (!array_key_exists($name, $this->_oldItems) || $value !== $this->_oldItems[$name])) {
                    $attributes[$name] = $value;
                }
            }
        }
        return $attributes;
    }
    //--------------------------------------------------------------------------
}