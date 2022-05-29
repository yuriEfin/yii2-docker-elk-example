<?php

namespace common\components\rabbitmq\router;

use common\components\rabbitmq\consumer\ExampleConsumer;
use common\components\rabbitmq\consumer\ExampleConsumer2;
use common\components\rabbitmq\consumer\ExampleConsumer3;
use common\components\rabbitmq\router\interfaces\RabbitMqRouterConfigAdapterInterface;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class RabbitMqRouterConfigAdapter implements RabbitMqRouterConfigAdapterInterface
{
    /**
     * @return array
     *
     * @example return Yii::$app->rabbitConfig->getConfig();
     *          OR return [ ths configuration for exchanges | queue | bindings | consumers ] with custom RabbitMqRouterConfigAdapterInterface (with DI container)
     */
    public function getConfig(): array
    {
        return [
            'exchange'  => [
                'defaultType'      => AMQPExchangeType::DIRECT,
                'app_auth'         => [
                    'type' => AMQPExchangeType::DIRECT,
                ],
                'app_1c'           => [
                    'type' => AMQPExchangeType::DIRECT,
                ],
                'app_tires'        => [
                    'type' => AMQPExchangeType::DIRECT,
                ],
                'app_orders'       => [
                    'type' => AMQPExchangeType::DIRECT,
                ],
                'app_disks'        => [
                    'type' => AMQPExchangeType::DIRECT,
                ],
                'app_email_sender' => [
                    'type' => AMQPExchangeType::DIRECT,
                ],
            ],
            'queues'    => [
                'app.auth'                     => [
                    'exchange' => 'app_auth',
                ],
                'app.1c.tires.order_report'    => [
                    'exchange' => 'app_1c',
                ],
                'app.tires'                    => [
                    'exchange' => 'app_tires',
                ],
                'app.orders.create'            => [
                    'exchange' => 'app_orders',
                ],
                'app.orders.notify_push'       => [
                    'exchange' => 'app_orders',
                ],
                'app.disks'                    => [
                    'exchange' => 'app_disks',
                ],
                'app.email_sender_auth_notify' => [
                    'exchange' => 'app_email_sender',
                ],
                'app.email_sender_news'        => [
                    'exchange' => 'app_email_sender',
                ],
                'app.email_sender_action'      => [
                    'exchange' => 'app_email_sender',
                ],
            ],
            'consumers' => [
                // for example -:)
                'app.email_sender_auth_notify' => ExampleConsumer2::class,
                'app.email_sender_news'        => ExampleConsumer::class,
                'app.email_sender_action'      => ExampleConsumer::class,
                'app.auth'                     => ExampleConsumer::class,
                'app.1c.tires.order_report'    => ExampleConsumer::class,
                'app.tires'                    => ExampleConsumer3::class,
                'app.orders.create'            => ExampleConsumer::class,
                'app.orders.notify_push'       => ExampleConsumer::class,
                'app.disks'                    => ExampleConsumer3::class,
            ],
        ];
    }
}