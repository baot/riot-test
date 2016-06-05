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

/*
 *  TOKEN BUCKET SETUP
 */
$storage = new FileStorage(__DIR__ . "/api.bucket");
$rate    = new Rate(1, Rate::SECOND);
$bucket  = new TokenBucket(1, $rate, $storage);
$consumer = new BlockingConsumer($bucket);
$bucket->bootstrap(1);

/*
 *  BOOTSTRAP OUT RIOTCLIENT
 */
$riotClient = new RiotClient(Config::RIOT_KEY);

/*
 *  BOOTSTRAP CONTROLLERS
 */
$regionController = new Controllers\RegionController($riotClient, $consumer);
$gameController = new Controllers\GameController($riotClient, $consumer);
$summonerController = new Controllers\SummonerController($riotClient, $consumer);
$championController = new Controllers\ChampionController($riotClient, $consumer);

/*
 *  KLEIN ROUTING
 */
$app = new \Klein\Klein();

$app->respond('GET', Config::SERVER_URL . 'regions', [
    $regionController, 'getAllRegions'
]);
$app->respond('GET', Config::SERVER_URL . '[:region]/games', [
    $gameController, 'getFeaturedGames'
]);
$app->respond('GET', Config::SERVER_URL . '[:region]/currentGame/[:summonerName]', [
    $gameController, 'getGameBySummonerName'
]);
$app->respond('GET', Config::SERVER_URL . '[:region]/records/by_id/[:summonerId]', [
    $summonerController, 'getRecordsOfSummonerById'
]);
$app->respond('GET', Config::SERVER_URL . '[:region]/champion/[:championId]', [
    $championController, 'getChampionById'
]);

$app->dispatch();
