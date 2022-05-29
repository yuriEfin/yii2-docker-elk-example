<?php

namespace common\components\rabbitmq\dto;

use yii\base\BaseObject;

class ConnectionDto extends BaseObject
{
    public ?string $host = null;
    public ?int $port = null;
    public ?string $user = null;
    public ?string $password = null;
    public $vhost = '/';
}