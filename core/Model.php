<?php

namespace Core;

use Core\Db;

class Model
{
    public function __get($value)
    {
        $methode = 'get' . ucfirst($value);
        $this->value = $this->$methode();
        return $this->value;
    }

    public static function one($name_table, $value, $class_name)
    {
        return Db::prepare('SELECT * FROM ' . $name_table . ' WHERE id = ?', [$value], $class_name, true, true);
    }

    public static function all($name_table, $name_class)
    {
        return Db::query('SELECT * FROM ' . $name_table,  $name_class);
    }
}

?>