<?php

namespace common\components\rabbitmq;

use common\components\rabbitmq\dto\ConnectionDto;
use common\components\rabbitmq\dto\publish\PublishDto;
use common\components\rabbitmq\interfaces\RabbitMqClientInterface;
use common\components\rabbitmq\interfaces\RabbitMqConnectionInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqClient implements RabbitMqClientInterface
{
    private ?AMQPStreamConnection $connection = null;
    private $channel = null;
    
    /**
     * @param RabbitMqConnectionInterface $connection
     * @param array                       $config
     */
    public function __construct(RabbitMqConnectionInterface $connection, array $config = [])
    {
        $this->connection = $connection->create(new ConnectionDto($config));
    }
    
    /**
     * @param string|null $id
     *
     * @return AMQPChannel
     */
    public function getChannel(string $id = null): AMQPChannel
    {
        if (empty($this->channel) || $this->channel->getChannelId() === null) {
            $this->channel = $this->connection->channel($id);
        }
        
        return $this->channel;
    }
    
    public function publish(PublishDto $publishData): bool
    {
        $messageDto = $publishData->message;
        $channel = $this->getChannel();
        
        $message = new AMQPMessage(json_encode($messageDto->getBody()), $messageDto->getOptions());
        
        $channel->basic_publish($message, $publishData->exchangeName);
        
        // @todo handle exceptions and return false
        return true;
    }
}