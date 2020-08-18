<?php
namespace core\db\conditions;
class AndCondition extends ConjunctionCondition {
    public function getOperator() {
        return 'AND';
    }
}