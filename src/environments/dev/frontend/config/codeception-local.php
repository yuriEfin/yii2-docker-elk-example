<?php

return yii\helpers\ArrayHelper::merge(
    require dirname(dirname(__DIR__)) . '/common/config/codeception-local.php',
    require __DIR__ . '/main.php',
    require __DIR__ . '/main-local.php',
    require __DIR__ . '/tests/test.php',
    require __DIR__ . '/tests/test-local.php',
    [
    ]
);
