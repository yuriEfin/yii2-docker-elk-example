<?php

use common\components\rabbitmq\consumer\YourConsumer;

return [
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'container' => [
        'definitions' => [
            'rabbit_mq.consumer.default' => YourConsumer::class,
        ],
        'singletons' => [
        
        ],
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'logstash' => [
            'class' => \mitrm\logstash\LogstashSend::class,
            'config' => [
                'class' => \mitrm\logstash\transport\TcpTransport::class,
                'socket' => 'tcp://logstash:8080'
            ],
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => 'http://elastic:9200'],
                // configure more hosts if you have a cluster
            ],
            // set autodetectCluster to false if you don't want to auto detect nodes
            // 'autodetectCluster' => false,
            'dslVersion' => 7, // default is 5
        ],
    ],
];
