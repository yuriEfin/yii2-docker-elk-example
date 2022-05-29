<?php

use common\components\rabbitmq\interfaces\RabbitMqClientInterface;
use common\components\rabbitmq\interfaces\RabbitMqConnectionInterface;
use common\components\rabbitmq\interfaces\RabbitMqManagerInterface;
use common\components\rabbitmq\RabbitMqClient;
use common\components\rabbitmq\RabbitMqConnection;
use common\components\rabbitmq\RabbitMqManager;
use common\components\rabbitmq\router\interfaces\RabbitMqRouterConfigAdapterInterface;
use common\components\rabbitmq\router\interfaces\RabbitMqRouterInterface;
use common\components\rabbitmq\router\RabbitMqRouter;
use common\components\rabbitmq\router\RabbitMqRouterConfigAdapter;

return [
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'container'  => [
        'definitions' => [
            RabbitMqClientInterface::class              => RabbitMqClient::class,
            RabbitMqManagerInterface::class             => RabbitMqManager::class,
            RabbitMqRouterInterface::class              => RabbitMqRouter::class,
            RabbitMqRouterConfigAdapterInterface::class => RabbitMqRouterConfigAdapter::class,
        ],
        'singletons'  => [
            RabbitMqConnectionInterface::class => RabbitMqConnection::class,
        ],
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache'         => [
            'class' => 'yii\caching\FileCache',
        ],
        'logstash'      => [
            'class'  => \mitrm\logstash\LogstashSend::class,
            'config' => [
                'class'  => \mitrm\logstash\transport\TcpTransport::class,
                'socket' => 'tcp://logstash:8080',
            ],
        ],
        'elasticsearch' => [
            'class'      => 'yii\elasticsearch\Connection',
            'nodes'      => [
                ['http_address' => 'http://elastic:9200'],
                // configure more hosts if you have a cluster
            ],
            // set autodetectCluster to false if you don't want to auto detect nodes
            // 'autodetectCluster' => false,
            'dslVersion' => 7, // default is 5
        ],
    ],
];
