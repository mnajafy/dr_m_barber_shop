<?php
namespace core\db\conditions;
use core\db\ExpressionInterface;
use core\db\ExpressionBuilderInterface;
class HashConditionBuilder extends ConditionBuilder implements ExpressionBuilderInterface {
    public function build($expression, &$params = []) {
        $hash  = $expression->getHash();
        $parts = [];
        foreach ($hash as $column => $value) {
            if ($value === null) {
                $parts[] = "$column IS NULL";
            }
            elseif ($value instanceof ExpressionInterface) {
                $parts[] = "$column=" . $this->queryBuilder->buildExpression($value, $params);
            }
            else {
                $phName  = $this->queryBuilder->bindParam($value, $params);
                $parts[] = "$column=$phName";
            }
        }
        return (empty($parts) ? '' : (count($parts) === 1 ? $parts[0] : '(' . implode(') AND (', $parts) . ')'));
    }
}