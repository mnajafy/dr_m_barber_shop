<?php
namespace core\db\conditions;
use core\db\ExpressionInterface;
use core\db\ExpressionBuilderInterface;
class SimpleConditionBuilder extends ConditionBuilder implements ExpressionBuilderInterface {
    public function build(ExpressionInterface $expression, array &$params = []) {
        $operator = $expression->getOperator();
        $column   = $expression->getColumn();
        $value    = $expression->getValue();

        if ($column instanceof ExpressionInterface) {
            $column = $this->queryBuilder->buildExpression($column, $params);
        }
//        elseif (is_string($column) && strpos($column, '(') === false) {
//            $column = $this->queryBuilder->db->quoteColumnName($column);
//        }

        if ($value === null) {
            return "$column $operator NULL";
        }

        if ($value instanceof ExpressionInterface) {
            return "$column $operator {$this->queryBuilder->buildExpression($value, $params)}";
        }

        $phName = $this->queryBuilder->bindParam($value, $params);
        return "$column $operator $phName";
    }
}