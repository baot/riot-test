<?php 
namespace Server;

require_once __DIR__ . '/../vendor/autoload.php';

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

use bandwidthThrottle\tokenBucket\Rate;
use bandwidthThrottle\tokenBucket\TokenBucket;
use bandwidthThrottle\tokenBucket\BlockingConsumer;
use bandwidthThrottle\tokenBucket\storage\FileStorage;

$storage = new FileStorage(__DIR__ . "/api.bucket");
$rate    = new Rate(1, Rate::SECOND);
$bucket  = new TokenBucket(1, $rate, $storage);
$consumer = new BlockingConsumer($bucket);
$bucket->bootstrap(1);

$riotClient = new RiotClient("27bec553-bfba-470e-a5c6-902fb6f0b0ff");
$app = new \Klein\Klein();

$regionController = new Controllers\RegionController($riotClient, $consumer);
$gameController = new Controllers\GameController($riotClient, $consumer);
$summonerController = new Controllers\SummonerController($riotClient, $consumer);
$championController = new Controllers\ChampionController($riotClient, $consumer);

$app->respond('GET', '/dev-test/server/regions', [$regionController, 'getAllRegions']);
$app->respond('GET', '/dev-test/server/[:region]/games', [$gameController, 'getFeaturedGames']);
$app->respond('GET', '/dev-test/server/[:region]/currentGame/[:summonerName]', [$gameController, 'getGameBySummonerName']);
//$app->respond('GET', '/dev-test/server/[:region]/records/by-name/[:summonerName]', [$summonerController, 'getRecordsOfSummonerByName']);
$app->respond('GET', '/dev-test/server/[:region]/records/by_id/[:summonerId]', [$summonerController, 'getRecordsOfSummonerById']);
$app->respond('GET', '/dev-test/server/[:region]/champion/[:championId]', [$championController, 'getChampionById']);

$app->dispatch();
