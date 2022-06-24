<?php
use App\Core\Application;

require_once dirname(__DIR__) . '/vendor/autoload.php';
$response = new Application();
$response->run();