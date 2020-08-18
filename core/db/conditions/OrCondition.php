<?php
namespace core\db\conditions;
class OrCondition extends ConjunctionCondition {
    public function getOperator() {
        return 'OR';
    }
}