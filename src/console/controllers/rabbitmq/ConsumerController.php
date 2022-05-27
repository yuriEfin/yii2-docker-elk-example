<?php

namespace console\controllers\rabbitmq;

use yii\console\Controller;

class ConsumerController extends Controller
{
    public function actionRun()
    {
    
    }
    
    public function actionConsume($queue, $exchange, )
    {
        $this->stdout('queue: "' . $queue . '" - consume run...');
    }
}