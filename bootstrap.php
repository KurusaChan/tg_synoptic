<?php
require_once(__DIR__ . '/vendor/autoload.php');

use Illuminate\Database\Capsule\Manager as Capsule;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => env('DB_HOST'),
    'database' => env('DB_DATABASE'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => 'utf8mb4',              // **for emoticons**
    'collation' => 'utf8mb4_unicode_ci',   // **for emoticons**
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
