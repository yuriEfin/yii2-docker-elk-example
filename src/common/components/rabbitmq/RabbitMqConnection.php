<?php

namespace common\components\rabbitmq;

use common\components\interfaces\RabbitMqConnectionInterface;
use common\components\rabbitmq\dto\ConnectionDto;
use common\components\rabbitmq\exceptions\InvalidConfigurationConnectionException;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMqConnection implements RabbitMqConnectionInterface
{
    private ?ConnectionDto $params = null;
    
    /**
     * @param null   $host
     * @param null   $port
     * @param null   $user
     * @param null   $password
     * @param string $vhost
     *
     * @return AMQPStreamConnection|null
     */
    public function create(?string $host = null, ?int $port = null, ?string $user = null, ?string $password = null, string $vhost = '/'): ?AMQPStreamConnection
    {
        $this->createParams(func_get_args());
        
        return new AMQPStreamConnection($this->params->host, $this->params->port, $this->params->user, $this->params->password, $this->params->vhost);
    }
    
    private function createParams(array $config = []): void
    {
        if ($config) {
            $this->params = new ConnectionDto($config);
        } else {
            $this->params = new ConnectionDto(
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
        
        if (!$this->params->host || !$this->params->port || !$this->params->user || !$this->params->password) {
            throw new InvalidConfigurationConnectionException('Invalid configuration: rabbitmq connection data');
        }
    }
}