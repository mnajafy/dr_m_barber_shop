<?php
namespace app\models;
use Framework;
use core\base\Model;
class LoginForm extends Model {
    public $username;
    public $password;
    public function rules() {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string', 'max' => 255],
        ];
    }
    public function login() {
        if (!$this->validate()) {
            return false;
        }
        /* @var $user User */
        $user = User::findOne(['username' => $this->username]);
        if (!$user) {
            $this->addError('username', 'Username is incorrect!');
            return false;
        }
        if ($user->password !== $this->password) {
            $this->addError('password', 'Password is incorrect!');
            return false;
        }
        return Framework::$app->user->login($user);
    }
}