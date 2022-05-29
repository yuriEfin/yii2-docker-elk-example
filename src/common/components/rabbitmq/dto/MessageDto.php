<?php

namespace common\components\rabbitmq\dto;

use PhpAmqpLib\Message\AMQPMessage;

class MessageDto
{
    private const DEFAULT_OPTIONS = [
        'content_type'  => 'text/plain',
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
    ];
    
    private array $body = [];
    private array $options = [];
    
    public function __construct(?array $body = [], ?array $options = [])
    {
        $this->body = $body;
        $this->options = array_merge(self::DEFAULT_OPTIONS, $options);
    }
    
    /**
     * @return array|null
     */
    public function getBody(): ?array
    {
        return $this->body;
    }
    
    /**
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }
}