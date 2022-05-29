<?php

namespace common\components\rabbitmq\consumer\interfaces;

use PhpAmqpLib\Message\AMQPMessage;

interface ConsumerInterface
{
    public function execute(AMQPMessage $message);
}