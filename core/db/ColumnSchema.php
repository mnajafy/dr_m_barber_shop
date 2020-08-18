<?php
namespace core\db;
use PDO;
use core\base\BaseObject;
class ColumnSchema extends BaseObject {
    public $name;
    public $allowNull;
    public $type;
    public $phpType;
    public $dbType;
    public $defaultValue;
    public $enumValues;
    public $size;
    public $precision;
    public $scale;
    public $isPrimaryKey;
    public $autoIncrement = false;
    public $unsigned;
    public $comment;
    public function phpTypecast($value) {
        return $this->typecast($value);
    }
    public function dbTypecast($value) {
        return $this->typecast($value);
    }
    protected function typecast($value) {
        if ($value === '' && !in_array($this->type, [Schema::TYPE_TEXT, Schema::TYPE_STRING, Schema::TYPE_BINARY, Schema::TYPE_CHAR], true)) {
            return null;
        }

        if ($value === null || gettype($value) === $this->phpType || $value instanceof ExpressionInterface || $value instanceof ActiveQuery) {
            return $value;
        }

        if (is_array($value) && count($value) === 2 && isset($value[1]) && in_array($value[1], $this->getPdoParamTypes(), true)) {
            return new PdoValue($value[0], $value[1]);
        }
        
        switch ($this->phpType) {
            case 'resource':
            case 'string':
                if (is_resource($value)) {
                    return $value;
                }
                if (is_float($value)) {
                    return StringHelper::floatToString($value);
                }
                return (string) $value;
            case 'integer':
                return (int) $value;
            case 'boolean':
                return (bool) $value && $value !== "\0";
            case 'double':
                return (float) $value;
        }

        return $value;
    }
    private function getPdoParamTypes() {
        return [PDO::PARAM_BOOL, PDO::PARAM_INT, PDO::PARAM_STR, PDO::PARAM_LOB, PDO::PARAM_NULL, PDO::PARAM_STMT];
    }
}