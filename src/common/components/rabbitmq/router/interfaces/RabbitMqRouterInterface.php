<?php

namespace common\components\rabbitmq\router\interfaces;

interface RabbitMqRouterInterface
{
    public function declareAll(): void;
    
    public function getConfig(): array;
}