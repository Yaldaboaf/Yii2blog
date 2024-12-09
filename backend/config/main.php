<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'class' => 'yii\web\Request',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ]
        ],
        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true]
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['site'],
                    'pluralize' => false,
                    'patterns' => [
                        'GET login' => 'login',
                        'POST login' => 'login',
                        'GET error' => 'error'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['user'],
                    'pluralize' => false,
                    'patterns' => [
                        'GET index' => 'index'
                    ]
                ],  
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['post'],
                    'pluralize' => false,
                    'patterns' => [ 
                        'GET index' => 'index'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/post'],
                    'pluralize' => false,
                    'patterns' => [
                        'POST login' => 'login',
                        'POST register' => 'register',
                        'POST create-post' => 'create-post',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/get'],
                    'pluralize' => false,
                    'patterns' => [
                        'GET posts' => 'posts',
                        'GET my-posts' => 'my-posts',
                    ]
                ]
            ]
        ],
    ],
    'params' => $params,
];
