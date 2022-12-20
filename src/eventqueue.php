<?php

require_once __DIR__ . '/vendor/autoload.php';
include("sendmail.php");

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

class EventQueue
{

    function requestSendMail($payload)
    {
        global $channel;
        global $connection;
        $channel->queue_declare('send.email.notification', false, false, false, false);
        // $msg['id_log'] = $id;
        // $msg['to'] = $to;
        // $msg['subject'] = $subject;
        // $msg['body'] = $body;
        $data = json_encode($payload);
        $queue = new AMQPMessage($data);
        $channel->basic_publish($queue, '', 'send.email.notification');


        // echo " [x] Sent 'Mail'\n";

        $channel->close();
        $connection->close();
    }

    function consumerEmail()
    {
        global $channel;
        global $connection;
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
    }
}
