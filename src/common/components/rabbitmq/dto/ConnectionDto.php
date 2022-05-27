<?php

namespace common\components\rabbitmq\dto;

use yii\base\BaseObject;

class ConnectionDto extends BaseObject
{
    public $host = '0.0.0.0';
    public $port = 5672;
    public $user = 'guest';
    public $password = 'guest';
    public $vhost = '/';
}