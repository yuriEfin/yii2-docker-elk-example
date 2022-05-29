<?php

namespace common\components\rabbitmq\dto\publish;

use common\components\rabbitmq\dto\MessageDto;

class PublishDto
{
    public ?string $exchangeName = null;
    public ?MessageDto $message = null;
    
    public function __construct(MessageDto $message, string $exchangeName)
    {
        $this->exchangeName = $exchangeName;
        $this->message = $message;
    }
}