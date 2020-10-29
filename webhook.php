<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once(__DIR__ . '/bootstrap.php');
(new \App\WebhookController())->handle();