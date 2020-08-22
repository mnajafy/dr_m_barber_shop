<?php
namespace app\models;
/**
 * @property string $username
 * @property string $password
 */
class User extends \core\db\ActiveRecord implements \core\web\IdentityInterface {
    public static function tablename() {
        return 'users';
    }
    public function getId() {
        return $this->id;
    }
    public static function findIdentity($id) {
        return static::findOne($id);
    }
}