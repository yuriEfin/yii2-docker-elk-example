<?php

namespace console\controllers\rabbitmq;

use common\components\rabbitmq\dto\ConsumeDto;
use common\components\rabbitmq\interfaces\RabbitMqManagerInterface;
use common\components\rabbitmq\router\interfaces\RabbitMqRouterInterface;
use Graze\ParallelProcess\Display\Lines;
use Graze\ParallelProcess\Pool;
use Graze\ParallelProcess\PoolInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;
use yii\console\Controller;
use yii\console\ExitCode;

class ConsumerController extends Controller
{
    private ?RabbitMqManagerInterface $rabbitMqManager = null;
    private ?RabbitMqRouterInterface $rabbitMqRouter = null;
    private ?PoolInterface $processManager = null;
    
    public function __construct($id, $module, RabbitMqManagerInterface $rabbitMqManager, RabbitMqRouterInterface $rabbitMqRouter, Pool $processManager, $config = [])
    {
        parent::__construct($id, $module, $config);
        
        $this->rabbitMqManager = $rabbitMqManager;
        $this->rabbitMqRouter = $rabbitMqRouter;
        $this->processManager = $processManager;
    }
    
    public function actionRun()
    {
        return ExitCode::OK;
    }
    
    public function actionConsume()
    {
        $routingConfig = $this->rabbitMqRouter->getConfig();
        foreach ($routingConfig['consumers'] as $queue => $consumer) {
            $cmd = 'php /src/yii rabbitmq/consumer/consume-queue ' . $queue . ' ' . (addslashes($consumer));
            
            $process = Process::fromShellCommandline($cmd);
    
            $this->processManager->add($process);
        }
        
        $this->startAsync();
        //$this->startAsyncWithOutput();

        return ExitCode::OK;
    }
    
    public function actionConsumeQueue($queueName, $consumer)
    {
        $this->rabbitMqManager->consume(
            new ConsumeDto($queueName, $consumer)
        );
    }
    
    /**
     *  Start parallel processes
     */
    public function startAsync(): void
    {
        $this->stdout('Process manager start pool' . PHP_EOL);
        
        $this->processManager->run();
    }
    
    /**
     * Start parallel processes with monitor
     */
    public function startAsyncWithOutput(): void
    {
        $this->stdout('Process manager start pool with monitor' . PHP_EOL);
        
        $output = new ConsoleOutput();
        $lines = new Lines($output, $this->processManager);
        
        $lines->run();
    }
}