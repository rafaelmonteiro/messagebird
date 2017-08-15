<?php

use App\Bootstrap;
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

define('BASE_DIR', realpath(__DIR__ . '/..'));
require_once(BASE_DIR . '/src/Bootstrap.php');

$app = new Bootstrap('dev', false);
$app->run();