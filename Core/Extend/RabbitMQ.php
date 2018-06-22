<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/1/12
 * Time: 14:11
 */

namespace Core\Extend;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;



class RabbitMQ
{
    private static $obj;
    public $config = [
        'host'=>'192.168.1.39',     //rabbitmq 服务器host
        'port'=>5672,               //rabbitmq 服务器端口
        'login'=>'david',           //登录用户
        'password'=>'david',        //登录密码
        'vhost'=>'/'                //虚拟主机
    ];
    public $exchangeName;
    public $queueName;
    public $keyRouter;
    public $connection;
    public $channel;
    public $ex;
    public $status;
    public $consumerTag;

    private function __construct($exchangeName = "edemo",$queueName = "qdemo",$keyRouter = "k_router")
    {
        $this->exchangeName = $exchangeName;
        $this->queueName = $queueName;
        $this->keyRouter = $keyRouter;
        $this->consumerTag = 'consumer';

        $this->connection = new AMQPStreamConnection($this->config['host'], $this->config['port'], $this->config['login'], $this->config['password'], $this->config['vhost']);
        $this->channel = $this->connection->channel();

    }

    public static function instance(){
        if(is_null(self::$obj)){
            self::$obj = new self;
        }
        return self::$obj;
    }

    public function publish($argv){
        $this->channel->queue_declare($this->queueName, false, true, false, false);
        $this->channel->exchange_declare($this->exchangeName, 'direct', false, true, false);
        $this->channel->queue_bind($this->queueName, $this->exchangeName);
        $messageBody = implode(' ', array_slice($argv, 1));
        $message = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $this->channel->basic_publish($message, $this->exchangeName);
    }

    public function consumer(){
        $callback = function($message){
            echo "\n--------\n";
            echo $message->body;
            echo "\n--------\n";
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            // Send a message with the string "quit" to cancel the consumer.
            if ($message->body === 'quit') {
                $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
            }
        };

        $this->channel->queue_declare($this->queueName, false, true, false, false);
        $this->channel->exchange_declare($this->exchangeName, 'direct', false, true, false);
        $this->channel->queue_bind($this->queueName, $this->exchangeName);
        $this->channel->basic_consume($this->queueName, $this->consumerTag, false, false, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

}