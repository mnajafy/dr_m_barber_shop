<?php
namespace app\models;
use core\db\ActiveRecord;
/**
 * Users
 * 
 * @property int $id
 * @property string $username
 * @property string $password
 */
class Test extends ActiveRecord {
    public static function tablename() {
        return 'users';
    }
    public function rules() {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string', 'max' => 255],
        ];
    }
//    public function labels() {
//        return [
//            'username' => 'نام کاربری',
//            'password' => 'رمز عبور',
//        ];
//    }
}