<?php
namespace core\db;
use core\base\BaseObject;
class TableSchema extends BaseObject {
    public $name;
    public $columns    = [];
    public $primaryKey = [];
    public $foreignKeys = [];
}