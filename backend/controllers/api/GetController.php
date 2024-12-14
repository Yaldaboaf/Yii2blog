<?php

namespace backend\controllers\api;

use Yii;
use yii\rest\Controller;
use backend\models\BlogPost;

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

        $posts = BlogPost::getPosts($limit, $offset);
        $total = BlogPost::getTotalCount();

        return [
            'success' => true,
            'data' => [
                'posts' => $posts,
                'total' => $total,
                'limit' => (int)$limit,
                'offset' => (int)$offset
            ]
        ];
    }

    public function actionMyPosts()
    {
        $username = Yii::$app->request->getBodyParam('username');
        $limit = Yii::$app->request->getBodyParam('limit', 10);
        $offset = Yii::$app->request->getBodyParam('offset', 0);

        if (!$username) {
            return [
                'success' => false,
                'message' => 'Не указан юзернейм'
            ];
        }

        $posts = BlogPost::getUserPosts($username, $limit, $offset);

        return [
            'success' => true,
            'data' => $posts
        ];
    }
} 