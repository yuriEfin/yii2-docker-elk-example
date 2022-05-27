<?php

namespace common\components\interfaces;

use PhpAmqpLib\Connection\AMQPStreamConnection;

interface RabbitMqConnectionInterface
{
    public function create(?string $host = null, ?int $port = null, ?string $user = null, ?string $password = null, string $vhost = '/'): ?AMQPStreamConnection;
}