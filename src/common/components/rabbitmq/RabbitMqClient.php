<?php

namespace common\components\rabbitmq;

use common\components\interfaces\RabbitMqClientInterface;
use common\components\interfaces\RabbitMqConnectionInterface;

class RabbitMqClient implements RabbitMqClientInterface
{
    private ?RabbitMqConnectionInterface $connection = null;
    
    public function __construct(RabbitMqConnectionInterface $connection)
    {
        $this->connection = $connection;
    }
}