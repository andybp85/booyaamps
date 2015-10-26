<?php
 UNCOMMENT ALL THIS:
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if(!IS_AJAX) {
  header("Location: http://" . $_SERVER['HTTP_HOST']);
  exit();
}

 DISABLE BEFORE PRODUCTION!!!!
header("Access-Control-Allow-Origin: http://booyaamps.com:9000");
// YES THIS ^^^^

require './vendor/autoload.php'; // grab the composer classes
Dotenv::load('..'); // grab the db connection info out of the environment

// set up the database connection; using Doctrine for future-proof-ness
$config = new \Doctrine\DBAL\Configuration();

$connectionParams = array(
  'dbname'   => $_ENV['BOOYA_DB'],
  'user'     => $_ENV['BOOYA_DB_USER'],
  'password' => $_ENV['BOOYA_DB_PASSWD'],
  'host'     => $_ENV['BOOYA_DB_HOST'],
  'driver'   => 'pdo_mysql',
);

$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

echo 'test';
