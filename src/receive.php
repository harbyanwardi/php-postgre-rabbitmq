<?php 
    require_once __DIR__ . '/vendor/autoload.php';
    include("sendmail.php");
    use PhpAmqpLib\Connection\AMQPStreamConnection;

    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('send.email.notification', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function ($msg) {
    //$data = json_decode($msg->body);
    $a = new SendMail();
    $a->sendEmail($msg->body);
    echo ' [x] Received ', $msg->body, "\n";
   
  };
  
  $channel->basic_consume('send.email.notification', '', false, true, false, false, $callback);
  
  while ($channel->is_open()) {
      $channel->wait();
  }


?>