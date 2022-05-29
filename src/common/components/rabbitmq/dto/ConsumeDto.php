<?php

namespace common\components\rabbitmq\dto;

class ConsumeDto
{
    public string $queue = '';
    public string $consumer = '';
    
    public function __construct(string $queue, string $consumer)
    {
        $this->queue = $queue;
        $this->consumer = $consumer;
    }
}