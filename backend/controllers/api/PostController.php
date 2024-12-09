<?php

namespace backend\controllers\api;

use Yii;
use yii\rest\Controller;
use backend\models\User;
use yii\web\Response;
use backend\models\BlogPost;
use backend\models\AccessToken;

class PostController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        $email = Yii::$app->request->getBodyParam('email');
        $password = Yii::$app->request->getBodyParam('password');
        
        if ($email === null || $password === null) {
            return [
                'success' => false,
                'message' => 'Email и пароль обязательны для заполнения'
            ];
        }

        $user = User::findOne(['email' => $email]);
        
        if (!$user || !$user->validatePassword($password)) {
            return [
                'success' => false,
                'message' => 'Неверный email или пароль'
            ];
        }
        
        $accessToken = new AccessToken();
        $accessToken->userId = $user->id;
        $accessToken->token = Yii::$app->security->generateRandomString(32);
        
        if ($accessToken->save()) {
            return [
                'success' => true,
                'accessToken' => $accessToken->token
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Ошибка при создании токена'
        ];
    }
    
    public function actionRegister()
    {
        $user = new User();
        $params = [];
        
        $params['userName'] = Yii::$app->request->getBodyParam('userName');
        $params['password'] = Yii::$app->request->getBodyParam('password');
        $params['email'] = Yii::$app->request->getBodyParam('email');

        if ( $params['email'] === null || $params['password'] === null || $params['userName'] === null) {
            return [
                'success' => false,
                'message' => 'Имя, Email и пароль обязательны для заполнения',
            ];
        }

        $params['passwordHash'] = Yii::$app->security->generatePasswordHash($params['password']);
        unset($params['password']);

        if (User::findOne(['email' => $params['email']])) {
            return [
                'success' => false,
                'message' => 'Пользователь с таким email уже существует'
            ];
        }
        // $user->load($params) && 
        $user->userName = $params['userName'];
        $user->passwordHash = $params['passwordHash'];
        $user->email = $params['email'];

        if ($user->save()) {
            $accessToken = new AccessToken();
            $accessToken->userId = $user->id;
            $accessToken->token = Yii::$app->security->generateRandomString(32);

            if ($accessToken->save()) {
                return [
                    'success' => true,
                    'accessToken' => $accessToken->token
                ];
            }
        }else {
            return [
                'success' => false,
                'errors' => $user->errors
            ];
        }
    }

    public function actionCreatePost()
    {
        $accessToken = Yii::$app->request->getBodyParam('accessToken');
        $text = Yii::$app->request->getBodyParam('text');

        if (!$accessToken || !$text) {
            return [
                'success' => false,
                'message' => 'Необходимо указать accessToken и текст публикации'
            ];
        }

        $token = AccessToken::findOne(['token' => $accessToken]);
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Недействительный токен доступа'
            ];
        }

        // Создаем новый пост
        $post = new BlogPost();
        $post->userId = $token->userId;
        $post->text = $text;

        if ($post->save()) {
            return [
                'success' => true,
                'message' => 'Пост успешно опубликован',
                'post_id' => $post->id
            ];
        }

        return [
            'success' => false,
            'message' => 'Ошибка при публикации поста',
        ];
    }
}
