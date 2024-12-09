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
            // Находим пользователя по email
            $user = User::findOne(['email' => $this->email]);

            if ($user && Yii::$app->security->validatePassword($this->password, $user->password_hash) && $user->isAdmin) {
                // Если пользователь найден, пароль верный и он администратор
                $accessToken = AccessToken::findOne(['userId' => $user->id]);

                if ($accessToken) {
                    // Логиним пользователя с использованием access token
                    Yii::$app->user->loginByAccessToken($accessToken->token);
                    return true; // Успешный вход
                }
            }
        }

        return false; // Неудачный вход
    }
}
