<?php

namespace common\components\rabbitmq\dto\queue;

class QueueDto
{
    public string $channel = '';
    public string $exchange = '';
    
    // -- queue properties
    public string $name = '';
    public bool $isPassive = false;
    public bool $isDurable = true;    // the queue will survive a broker restart - https://www.rabbitmq.com/queues.html
    public bool $isExclusive = false; // for only current connection - after disconnect (close current connect) this queue will be delete
    public bool $isAutoDelete = true; // queue that has had at least one consumer is deleted when last consumer unsubscribes
    public bool $isNoWait = false;
    /**
     * x-message-ttl - время жизни сообщения в сек
     * x-expires - время жизни очереди
     * x-max-length - время жизни очереди
     * x-max-lenght-bytes - суммарный объем полезной инфо всех сообщений в очереди
     * x-overflow - реакция на переполнение объема полезн инфо (x-max-lenght-bytes) - 2 варианта : drop-head / reject-publish
     * x-dead-letter-exchange - имя обменника куда отправятся очереди отвергнутые по причине (x-max-lenght-bytes)
     * x-dead-letter-routing-key - ключ маршр-ции для отвергнутых сообщений
     * x-max-priority - сортировка по приоритетам, макс значение 255
     * x-queue-mode - режим очереди (ленивый - хранение на диске, скорость обработки ниже, иногда полезно)
     * x-queue-master-locator - если кластер , то задает мастер очередь
     * x-ha-policy - для HA (Highly Available) очередей и определяет как сообщение будет распространяться по узлам
     * x-ha-nodes -  для HA (Highly Available) - задает узлы, к которым будет относиться некая очередь HA
     */
    public array $options = []; // options (x-arguments) for declared queue
    public ?int $ticket = null;
    
    public function __construct(
        string $name,
        bool $isPassive = false,
        bool $isDurable = true,
        bool $isExclusive = false,
        bool $isAutoDelete = true,
        bool $isNoWait = false
    ) {
        $this->name = $name;
        $this->isPassive = $isPassive;
        $this->isDurable = $isDurable;
        $this->isExclusive = $isExclusive;
        $this->isAutoDelete = $isAutoDelete;
        $this->isNoWait = $isNoWait;
    }
}