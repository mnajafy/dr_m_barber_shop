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
//            [['username', 'password'], 'asd'],
//            [['username', 'password'], ['class' => '\app\In']],
//            [['username', 'password'], function ($model, $attribute, $value) {
////                $model->addError('password', 'asd');
//            }],
            [['username', 'password'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['password'], 'number'],
        ];
    }
    public function asd() {
//        $this->addError('username', 'asd');
    }
    public function labels() {
        return [
            'username' => 'نام کاربری',
            'password' => 'رمز عبور',
        ];
    }
}