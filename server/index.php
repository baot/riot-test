<?php 
namespace Server;

require_once __DIR__ . '/../vendor/autoload.php';

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

$riotClient = new RiotClient("27bec553-bfba-470e-a5c6-902fb6f0b0ff");
$app = new \Klein\Klein();

$regionController = new Controllers\RegionController($riotClient, $consumer);

$app->respond('GET', '/dev-test/server/regions', [$regionController, 'getAllRegions']);

$app->dispatch();
