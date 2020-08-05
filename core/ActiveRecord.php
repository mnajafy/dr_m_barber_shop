<?php
namespace Core;
use PDO;
use Framework;
class ActiveRecord extends Model {
    /**
     * @var array attribute values indexed by attribute names
     */
    private $_attributes = [];
    /**
     * @var array|null old attribute values indexed by attribute names.
     * This is `null` if the record [[isNewRecord|is new]].
     */
    private $_oldAttributes;
    //
    public static function tablename() {
        return strtolower(basename(get_called_class()));
    }
    public static function one($id) {
        $prepare = Framework::$app->db->pdo->prepare('SELECT * FROM ' . static::tablename() . ' WHERE id = ?');
        $prepare->execute([$id]);
        $row     = $prepare->fetch(PDO::FETCH_ASSOC);
        $model   = new static();
        return $row ? $model->populate($row) : null;
    }
    public static function all() {
        $prepare = Framework::$app->db->pdo->prepare('SELECT * FROM ' . static::tablename());
        $prepare->execute();
        $rows    = $prepare->fetchAll(PDO::FETCH_ASSOC);
        $models  = [];
        foreach ($rows as $row) {
            $model    = new static();
            $models[] = $model->populate($row);
        }
        return $models;
    }
    public static function runOne($sql, $params = []) {
        $prepare = Framework::$app->db->pdo->prepare($sql);
        $prepare->execute($params);
        $row     = $prepare->fetch(PDO::FETCH_ASSOC);
        $model   = new static();
        return $row ? $model->populate($row) : null;
    }
    public static function runAll($sql, $params = []) {
        $prepare = Framework::$app->db->pdo->prepare($sql);
        $prepare->execute($params);
        $rows    = $prepare->fetchAll(PDO::FETCH_ASSOC);
        $models  = [];
        foreach ($rows as $row) {
            $model    = new static();
            $models[] = $model->populate($row);
        }
        return $models;
    }
    //
    public function populate($row) {
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
    public function attributes() {
        
    }
    public function delete() {
        return false;
    }
    public function save() {
        return false;
    }
    //
}