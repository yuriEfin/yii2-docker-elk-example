<?php

namespace common\components\rabbitmq\interfaces;

use PhpAmqpLib\Channel\AMQPChannel;

interface RabbitMqClientInterface
{
    public function getChannel(string $id = null): AMQPChannel;
}