<?php

namespace common\components\rabbitmq\consumer;

use mikemadisonweb\rabbitmq\components\Consumer;
use mikemadisonweb\rabbitmq\components\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class YourConsumer extends Consumer
{
    /**
     * @param AMQPMessage $msg
     *
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        $data = $msg->body;
        
        // Apply your business logic here
        
        return ConsumerInterface::MSG_ACK;
    }
}