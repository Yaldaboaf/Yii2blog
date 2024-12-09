<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\AccessToken;
use backend\models\User;

class LoginForm extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
        ];
    }
    public function login()
    {
        if ($this->validate()) {
            $user = User::findOne(['email' => $this->email]);

            if ($user && Yii::$app->security->validatePassword($this->password, $user->passwordHash)) {
                if ($user->authKey) {
                    Yii::$app->user->login($user);
                    return true; 
                }
            }
        }

        return false; 
    }

}
