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
  - consume all queue with configuration - actionConsume - config - `src/common/components/rabbitmq/router/RabbitMqRouterConfigAdapter.php`

![image](https://user-images.githubusercontent.com/5247130/170888937-419aac6c-2b77-4ac7-9d5d-66764980db11.png)


# supervisor
- daemon config docker/supervisor/rabbitmq_cosume.conf

![image](https://user-images.githubusercontent.com/5247130/170888819-8826d870-1cba-48f7-8811-0980be5fbf39.png)

- changed count process daemon (`numprocs=5` in config `rabbitmq_cosume.conf`)

![image](https://user-images.githubusercontent.com/5247130/170888878-a5e013c6-fadf-445f-943f-14e548d4fc59.png)



