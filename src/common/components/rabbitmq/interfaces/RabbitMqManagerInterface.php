<?php

namespace common\components\rabbitmq\interfaces;

use common\components\rabbitmq\dto\ConsumeDto;
use common\components\rabbitmq\dto\MessageDto;
use common\components\rabbitmq\dto\publish\PublishDto;
use common\components\rabbitmq\dto\queue\QueueDto;

interface RabbitMqManagerInterface
{
    public function consume(ConsumeDto $data): bool;
    
    public function publish(PublishDto $publishData): bool;
}