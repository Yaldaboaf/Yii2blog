<?php

namespace backend\controllers\api;

use Yii;
use yii\rest\Controller;
use backend\models\User;
use backend\models\AccessToken;
use backend\models\BlogPost;

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

        return User::login($email, $password);
    }

    public function actionRegister()
    {
        $params = Yii::$app->request->getBodyParams();
        return User::register($params);
    }

    public function actionCreatePost()
    {
        $accessToken = Yii::$app->request->getBodyParam('accessToken');
        $text = Yii::$app->request->getBodyParam('text');

        return BlogPost::createPost($accessToken, $text);
    }
}
