<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            $user = User::findOne(['email' => $this->email]);

            if ($user && Yii::$app->security->validatePassword($this->password, $user->passwordHash)) {
                Yii::$app->user->login($user);
                return true;
            }
        }

        return false;
    }
}
