<?php

namespace common\components\rabbitmq;

use common\components\rabbitmq\consumer\exceptions\UnresolveConsumerException;
use common\components\rabbitmq\consumer\interfaces\ConsumerInterface;
use common\components\rabbitmq\dto\ConsumeDto;
use common\components\rabbitmq\dto\publish\PublishDto;
use common\components\rabbitmq\interfaces\RabbitMqClientInterface;
use common\components\rabbitmq\interfaces\RabbitMqManagerInterface;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class RabbitMqManager implements RabbitMqManagerInterface
{
    private ?RabbitMqClientInterface $client;
    
    public function __construct(RabbitMqClientInterface $client)
    {
        $this->client = $client;
    }
    
    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     * @throws UnresolveConsumerException
     */
    public function consume(ConsumeDto $data): bool
    {
        /** @var ConsumerInterface $consumer */
        $consumer = $this->resolveConsumer($data);
        
        $channel = $this->client->getChannel();
        $channel->basic_qos(null, 20, null);
        $channel->basic_consume($data->queue, uniqid('rabbitmq_'), false, false, false, false, function (AMQPMessage $msg) use ($consumer) {
            $consumer->execute($msg);
        });
        
        if ($channel->is_open()) {
            $channel->wait(null, true);
        }
        
        return true;
    }
    
    /**
     * @param PublishDto $publishData
     *
     * @return bool
     * @throws AMQPConnectionClosedException
     */
    public function publish(PublishDto $publishData): bool
    {
        //$this->before();
        return $this->client->publish($publishData);
        //$this->after();
    }
    
    /**
     * @param ConsumeDto $data
     *
     * @return object
     * @throws UnresolveConsumerException
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function resolveConsumer(ConsumeDto $data)
    {
        if (empty($data->consumer)) {
            throw new UnresolveConsumerException('rabbitmq: failed create consumer by empty string');
        }
        
        // with DI container
        if (Yii::$container->has($data->consumer)) {
            return Yii::$container->get($data->consumer);
        }
        
        // with new object
        return new $data->consumer();
    }
}