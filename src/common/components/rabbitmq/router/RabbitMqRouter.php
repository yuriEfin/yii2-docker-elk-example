<?php

namespace common\components\rabbitmq\router;

use common\components\rabbitmq\exceptions\InvalidConfigurationRoutesException;
use common\components\rabbitmq\interfaces\RabbitMqConnectionInterface;
use common\components\rabbitmq\router\interfaces\RabbitMqRouterConfigAdapterInterface;
use common\components\rabbitmq\router\interfaces\RabbitMqRouterInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class RabbitMqRouter implements RabbitMqRouterInterface
{
    private ?AMQPStreamConnection $connection = null;
    private ?RabbitMqRouterConfigAdapterInterface $configAdapter = null;
    private array $config = [];
    
    /**
     * @param RabbitMqConnectionInterface          $rabbitMqConnection
     * @param RabbitMqRouterConfigAdapterInterface $configAdapter
     */
    public function __construct(RabbitMqConnectionInterface $rabbitMqConnection, RabbitMqRouterConfigAdapterInterface $configAdapter)
    {
        $this->connection = $rabbitMqConnection->create();
        $this->configAdapter = $configAdapter;
        $this->config = $this->configAdapter->getConfig();
    }
    
    public function declareAll(): void
    {
        $this->assertConfig();
        
        $this->declareChannel();
        $this->declareQueue();
        $this->bindQueueToChannel();
        $this->bindChannelToChannel();
    }
    
    public function getConfig(): array
    {
        return $this->config;
    }
    
    private function declareChannel()
    {
        $defaultType = $this->config['exchange']['defaultType'] ?? AMQPExchangeType::DIRECT;
        unset($this->config['exchange']['defaultType']);
        
        foreach ($this->config['exchange'] as $exchangeName => $properties) {
            $this->connection->channel()->exchange_declare($exchangeName, $defaultType, false, true, false);
        }
    }
    
    private function declareQueue()
    {
        foreach ($this->config['queues'] as $queueName => $properties) {
            $this->connection->channel()->queue_declare($queueName, false, true, false, false);
        }
    }
    
    private function bindQueueToChannel()
    {
        foreach ($this->config['queues'] as $queueName => $properties) {
            $this->connection->channel()->queue_bind($queueName, $properties['exchange']);
        }
    }
    
    private function declareConsumers()
    {
        foreach ($this->config['queues'] as $queueName => $properties) {
            $this->connection->channel()->queue_bind($queueName, $properties['exchange']);
        }
    }
    
    private function bindChannelToChannel()
    {
        //@_@ to be continue -:)
    }
    
    /**
     * @return void
     * @throws InvalidConfigurationRoutesException
     */
    private function assertConfig()
    {
        if (!isset($this->config['channels']) && !isset($this->config['queues'])) {
            throw new InvalidConfigurationRoutesException('rabbitMq: Invalid config for routing');
        }
    }
}