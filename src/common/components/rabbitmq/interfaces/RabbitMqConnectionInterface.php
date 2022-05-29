<?php

namespace common\components\rabbitmq\interfaces;

use common\components\rabbitmq\dto\ConnectionDto;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * @var AMQPStreamConnection $this
 */
interface RabbitMqConnectionInterface
{
    public function create(?ConnectionDto $config): ?AMQPStreamConnection; // config: ?string $host = null, ?int $port = null, ?string $user = null, ?string $password = null, string $vhost = '/')
}