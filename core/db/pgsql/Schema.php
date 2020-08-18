<?php
namespace core\db\pgsql;
use core\base\BaseObject;
class Schema extends \core\db\Schema {
    public function getQueryBuilder() {
        if ($this->_builder === null) {
            $this->_builder = BaseObject::createObject(['class' => QueryBuilder::class]);
        }
        return $this->_builder;
    }
}