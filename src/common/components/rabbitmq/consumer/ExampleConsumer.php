<?php

namespace common\components\rabbitmq\consumer;

use common\components\rabbitmq\consumer\interfaces\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class ExampleConsumer implements ConsumerInterface
{
    /**
     * @param AMQPMessage $msg
     *
     * @return bool
     */
    public function execute(AMQPMessage $message)
    {
        try {
            $data = $message->body;
    
            echo "\n".$message->getRoutingKey() . "\n";
            echo "\n Success handle $data \n";
            
            $message->ack();
        } catch (\Throwable $exception) {
            $message->nack(); // or $message->reject() for deleted message
            
            throw $exception;
        }
    }
}