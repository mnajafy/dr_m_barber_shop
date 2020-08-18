<?php
namespace core\db\conditions;
use core\base\BaseObject;
class ConditionBuilder extends BaseObject {
    /**
     * @var \core\db\QueryBuilder
     */
    public $queryBuilder;
    public function __construct($queryBuilder) {
        $this->queryBuilder = $queryBuilder;
    }
}