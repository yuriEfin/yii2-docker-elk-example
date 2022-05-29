<?php

namespace common\components\rabbitmq;

use common\components\rabbitmq\dto\ConnectionDto;
use common\components\rabbitmq\exceptions\InvalidConfigurationConnectionException;
use common\components\rabbitmq\interfaces\RabbitMqConnectionInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMqConnection implements RabbitMqConnectionInterface
{
    private ?ConnectionDto $config = null;
    private ?AMQPStreamConnection $connection = null;
    
    /**
     * Create new connection
     *
     * @var bool
     */
    private bool $isForceConnect = false;
    
    /**
     * @param ConnectionDto|null $config
     *
     * @return AMQPStreamConnection|null
     * @throws InvalidConfigurationConnectionException
     */
    public function create(?ConnectionDto $config = null): ?AMQPStreamConnection
    {
        $this->config = $config;
        if (!$this->assertConfig()) {
            $this->createConfigWithEnv();
        }
        
        $this->assertConfig(true);
        
        if (!$this->connection || $this->isForceConnect) {
            $this->connection = new AMQPStreamConnection($this->config->host, $this->config->port, $this->config->user, $this->config->password, $this->config->vhost);
        }
        
        return $this->connection;
    }
    
    /**
     * @param array $config
     *
     * @return void
     */
    private function createConfigWithEnv(): void
    {
        $this->config = new ConnectionDto(
            [
                // @todo: add to ENV vars PREFIX by host
                'host'     => getenv('RABBIT_MQ_HOST'), // '0.0.0.0'
                'port'     => getenv('RABBIT_MQ_PORT'), // '5672'
                'user'     => getenv('RABBIT_MQ_USER'),
                'password' => getenv('RABBIT_MQ_PASSWORD'),
                'vhost'    => '/',
            ]
        );
    }
    
    private function assertConfig(bool $isThrow = false): bool
    {
        if (!$this->config->host || !$this->config->port || !$this->config->user || !$this->config->password) {
            if (!$isThrow) {
                return false;
            }
            
            throw new InvalidConfigurationConnectionException('Invalid configuration: rabbitmq connection data');
        }
        
        return true;
    }
}