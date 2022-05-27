<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/local/params-local.php',
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
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => \mitrm\logstash\LogstashTarget::class,
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET', '_POST', '_SESSION', '_SERVER'],
                    'clientOptions' => [
                        'release' => 'backend_app',
                    ],
                    'isLogUser' => true, // Добавить в лог ID пользователя
                    'isLogContext' => false,
                    'extraCallback' => function ($message, $extra) {
                        $extra['app_id'] = Yii::$app->id;
                        $extra['GET'] = json_encode($_GET);
                        $extra['POST'] = json_encode($_POST);
                        $extra['COOKIE'] = json_encode($_COOKIE);
                        $extra['SESSION'] = json_encode($_SESSION);
                        $extra['SERVER'] = json_encode($_SERVER);
                        return $extra;
                    },
                    'except' => ['order'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
