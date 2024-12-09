<?php

namespace backend\controllers;

use Yii;
use backend\models\LoginForm;
use backend\models\User;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use backend\controllers\api\PostController;
use yii\filters\auth\HttpBasicAuth;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            // 'access' => [
            //     'class' => AccessControl::class,
            //     'rules' => [
            //         [
            //             'allow' => true,
            //             'roles' => ['?']
            //         ],
            //     ],
            // ],
            // 'verbs' => [
            //     'class' => VerbFilter::class,
            //     'actions' => [
            //         'logout' => ['post'],
            //     ],
            // ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'blank';
        $model = new LoginForm();

        // if (!Yii::$app->user->isGuest) {
        //     return $this->goHome();
        // }
        

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
                $model->email = '';
                $model->password = '';
                return $this->redirect(['user/index']);
            }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
