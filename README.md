# yii2-advanced-docker example

# structure
- docker - source docker containers
- src - source application

# target 
- docker infrastructure 
- ELK (filebeat for log webservers and log application)
- docker web host config (frontend|backend) (add to /etc/hosts frontend.work|backend.work)
- RabbitMQ - classic example use

# RabbitMQ broker messages
- source `src/common/components/rabbitmq/*`
- example use `src/console/controllers/rabbitmq/ExampleController.php`
- consume to queue - `src/console/controllers/rabbitmq/ConsumerController.php`
  - consume concrete queue - actionConsumeQueue
  - consume all queue with configuration (`src/common/components/rabbitmq/router/RabbitMqRouterConfigAdapter.php`)

# supervisor
- daemon config docker/supervisor/rabbitmq_cosume.conf


