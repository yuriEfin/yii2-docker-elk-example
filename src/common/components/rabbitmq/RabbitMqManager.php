<?php

namespace common\components\rabbitmq;

use common\components\interfaces\RabbitMqClientInterface;

class RabbitMqManager
{
    private ?RabbitMqClientInterface $client;
    
    public function __construct(RabbitMqClientInterface $client)
    {
        $this->client = $client;
    }
}