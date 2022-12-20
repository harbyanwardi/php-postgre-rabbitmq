<?php
require_once "connection.php";
include("eventqueue.php");

// Import script autoload agar bisa menggunakan library
require_once('./vendor/autoload.php');
// Import library
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

// Load dotenv
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Atur jenis response
header('Content-Type: application/json');



$headers = getallheaders();

// Periksa apakah header authorization-nya ada
if (!isset($headers['Authorization'])) {
   echo json_encode([
      'success' => false,
      'data' => null,
      'message' => 'Unauthorized'
    ]);
   http_response_code(401);
   exit();
}

// Mengambil token
list(, $token) = explode(' ', $headers['Authorization']);

try {
  // Men-decode token. Dalam library ini juga sudah sekaligus memverfikasinya
  JWT::decode($token, $_ENV['ACCESS_TOKEN_SECRET'], ['HS256']);
  
  
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'data' => null,
    'message' => 'Unauthorized'
  ]);
  // Bagian ini akan jalan jika terdapat error saat JWT diverifikasi atau di-decode
  http_response_code(401);
  exit();
}

if (function_exists($_GET['function'])) {
   $_GET['function']();
}
function getLog()
{
   global $connect;
   $sql = "select * from log_email";
   $data = pg_query($sql);
   $data = pg_fetch_object($data);
   $response = array(
      'status' => true,
      'message' => 'Success',
      'data' => $data
   );
   //print_r($data);
   // $data = $connect->prepare($sql);
   header('Content-Type: application/json');

   echo json_encode($response);
}

function sendEmail()
{
   global $connect;
   $check = array('email_to' => '', 'subject_email' => '', 'body_email' => '');
   $check_match = count(array_intersect_key($_POST, $check));
   if ($check_match == count($check)) {

      $sql = "INSERT INTO log_email(email_to,subject_email,body_email) VALUES (
               '".$_POST['email_to']."','".
              $_POST['subject_email']."','".
              $_POST['body_email']."')";
      $resp = pg_affected_rows(pg_query($sql));
      

      if ($resp) {
         $sql = "SELECT currval('log_email_id_seq') AS lastinsertid;";
         $data = pg_query($sql);
         $lastId = pg_fetch_object($data);
         $id_log = $lastId->lastinsertid;
         $a = new EventQueue();
         $payload = array(
            "id_log" => $id_log,
            "to" => $_POST['email_to'],
            "subject" => $_POST['subject_email'],
            "body" => $_POST['body_email'],
         );
         $a->requestSendMail($payload);
         $response = array(
            'status' => 1,
            'message' => 'Send Email Success'
         );
      } else {
         $response = array(
            'status' => 0,
            'message' => 'Send Email Failed.'
         );
      }
   } else {
      $response = array(
         'status' => 0,
         'message' => 'Wrong Parameter'
      );
   }
   header('Content-Type: application/json');
   echo json_encode($response);
}

// function listenerEmail() {
//    $a = new EventQueue();
//    $a->consumerEmail();
//    $response = array(
//       'status' => 1,
//       'message' => 'Listener Started...'
//    );
//    header('Content-Type: application/json');
//    echo json_encode($response);
// }
  