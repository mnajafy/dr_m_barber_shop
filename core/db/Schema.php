<?php
namespace core\db;
use core\base\BaseObject;
class Schema extends BaseObject {
    const TYPE_PK = 'pk';
    const TYPE_UPK = 'upk';
    const TYPE_BIGPK = 'bigpk';
    const TYPE_UBIGPK = 'ubigpk';
    const TYPE_CHAR = 'char';
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_TINYINT = 'tinyint';
    const TYPE_SMALLINT = 'smallint';
    const TYPE_INTEGER = 'integer';
    const TYPE_BIGINT = 'bigint';
    const TYPE_FLOAT = 'float';
    const TYPE_DOUBLE = 'double';
    const TYPE_DECIMAL = 'decimal';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TIMESTAMP = 'timestamp';
    const TYPE_TIME = 'time';
    const TYPE_DATE = 'date';
    const TYPE_BINARY = 'binary';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_MONEY = 'money';
    const TYPE_JSON = 'json';
    private $_tableMetadata = [];
    protected function getTableMetadata($name, $type) {
        if (!isset($this->_tableMetadata[$name][$type])) {
            $this->_tableMetadata[$name][$type] = $this->{'loadTable' . ucfirst($type)}($name);
        }
        return $this->_tableMetadata[$name][$type];
    }
    /**
     * @return TableSchema
     */
    public function getTableSchema($name) {
        return $this->getTableMetadata($name, 'schema');
    }
    /**
     * @return TableSchema
     */
    protected function loadTableSchema($name) {
        
    }
    private $_builder;
    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder() {
        if ($this->_builder === null) {
            $this->_builder = BaseObject::createObject(['class' => QueryBuilder::class]);
        }
        return $this->_builder;
    }
}