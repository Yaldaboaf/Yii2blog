<?php

namespace backend\controllers\api;

use Yii;
use yii\rest\Controller;
use backend\models\BlogPost;
use backend\models\AccessToken;
use backend\models\User;

class GetController extends Controller
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

    public function actionPosts()
    {
        $limit = Yii::$app->request->getBodyParam('limit', 10);
        $offset = Yii::$app->request->getBodyParam('offset', 0);

        $posts = BlogPost::find()
            ->with('user')
            ->orderBy(['createdAt' => SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->all();

        $total = BlogPost::find()->count();

        return [
            'success' => true,
            'data' => [
                'posts' => array_map(function($post) {
                    return $post->toArray([], ['user']);
                }, $posts),
                'total' => $total,
                'limit' => (int)$limit,
                'offset' => (int)$offset
            ]
        ];
    }

    public function actionMyPosts()
    {
        $username = Yii::$app->request->getBodyParams('username');
        $limit = Yii::$app->request->getBodyParams('limit', 10);
        $offset = Yii::$app->request->getBodyParams('offset', 0);

        if (!$username) {
            return [
                'success' => false,
                'message' => 'Не указан юзернейм'
            ];
        }

        $user = User::findOne(['username' => $username]);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Недействительный юзернейм'
            ];
        }

        $posts = BlogPost::find()
            ->with('user')
            ->where(['user_id' => $user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->all();

        $total = BlogPost::find()
            ->where(['user_id' => $user->id])
            ->count();

        return [
            'success' => true,
            'data' => [
                'posts' => array_map(function($post) {
                    return $post->toArray([], ['user']);
                }, $posts),
                'total' => $total,
                'limit' => (int)$limit,
                'offset' => (int)$offset
            ]
        ];
    }
} 