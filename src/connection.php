<?php
require_once('vendor/autoload.php');
use Dotenv\Dotenv;

// Load dotenv
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$hostname = $_ENV['DB_HOST'];
$database = $_ENV['DB_NAME'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$port = $_ENV['DB_PORT'];
// $connect = mysqli_connect($hostname, $username, $password, $database);
// $connect = new PDO("pgsql:dbname=$database;host=$hostname", $username, $password); 
$connection_string = "host={$hostname} port={$port} dbname={$database} user={$username} password={$password} ";
$connect = pg_connect($connection_string); 
// script cek koneksi   
if (!$connect) {
    die("Database Can't Connect ");
}
