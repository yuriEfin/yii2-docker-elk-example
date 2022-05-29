<?php

namespace common\components\rabbitmq\router\interfaces;

interface RabbitMqRouterConfigAdapterInterface
{
    public function getConfig(): array;
}