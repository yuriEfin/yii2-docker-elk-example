<?php

namespace console\controllers\rabbitmq;

use common\components\rabbitmq\dto\MessageDto;
use common\components\rabbitmq\dto\publish\PublishDto;
use common\components\rabbitmq\interfaces\RabbitMqManagerInterface;
use common\components\rabbitmq\router\RabbitMqRouterConfigAdapter;
use yii\console\Controller;
use yii\console\ExitCode;

class ExampleController extends Controller
{
    private ?RabbitMqManagerInterface $rabbitMqManager = null;
    
    public function __construct($id, $module, RabbitMqManagerInterface $rabbitMqManager, $config = [])
    {
        parent::__construct($id, $module, $config);
        
        $this->rabbitMqManager = $rabbitMqManager;
    }
    
    public function actionPublishTest(string $exchangeName, $message = 'Test')
    {
        $publishData = new PublishDto(
            new MessageDto(['message' => $message]),
            $exchangeName
        );
        $this->rabbitMqManager->publish($publishData);
        $this->stdout('queue: "{' . $message . '}" - consume run...' . PHP_EOL);
        
        return ExitCode::OK;
    }
    
    public function actionPublishTestByConfigExchange($message = 'Test', $limitMessage = 100)
    {
        $configData = (new RabbitMqRouterConfigAdapter())->getConfig();
        
        foreach ($configData['exchange'] as $exchangeName => $props) {
            if ($exchangeName == 'defaultType') {
                continue;
            }
            $this->stdout('exchangeName: "' . $exchangeName . '" - publish start...' . PHP_EOL);
    
            for ($i = 0; $i <= $limitMessage; $i++) {
                $publishData = new PublishDto(
                    new MessageDto(['message' => $message . ', index: ' . $i, 'index' => $i]),
                    $exchangeName
                );
                $this->rabbitMqManager->publish($publishData);
            }
            $this->stdout('exchangeName: "' . $exchangeName . '" - publish finished...' . PHP_EOL);
        }
        
        return ExitCode::OK;
    }
}