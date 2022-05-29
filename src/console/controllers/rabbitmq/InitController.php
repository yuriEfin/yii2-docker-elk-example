<?php

namespace console\controllers\rabbitmq;

use common\components\rabbitmq\router\interfaces\RabbitMqRouterInterface;
use yii\console\Controller;
use yii\console\ExitCode;

class InitController extends Controller
{
    private ?RabbitMqRouterInterface $rabbitMqRouter;
    
    public function __construct($id, $module, RabbitMqRouterInterface $router, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->rabbitMqRouter = $router;
    }
    
    public function actionDeclare()
    {
        $this->rabbitMqRouter->declareAll();
        
        return ExitCode::OK;
    }
}