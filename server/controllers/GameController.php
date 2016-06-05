<?php 
namespace Server\Controllers;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

use Server\Models\Game;

class GameController extends AbstractController
{

    /*
     * platformId - region list
     *
     * @var array
     */
    protected $platformList = [
        'BR1' => 'br',
        'EUN1' => 'eune',
        'EUW1' => 'euw',
        'JP1' => 'jp',
        'KR' => 'kr',
        'LA1' => 'lan',
        'LA2' => 'las',
        'NA1' => 'na',
        'OC1' => 'oce',
        'TR1' => 'tr',
        'RU' => 'ru',
        'PBE1' => 'pbe'
    ];

    /*
     * Get all featuredGames
     * NOTE: cannot rely on region for featureGames-> check also game platformId
     *
     * @param Klein\Request $req
     * @param Klein\Response $resp
     * @param Klein\ServiceProvider $service
     */
    public function getFeaturedGames(Request $req, Response $resp, ServiceProvider $service)
    {
        $region = $req->paramsNamed()->get('region', null);
        if (is_null($region)) {
            $this->makeErrorResponse($resp, 404);
        } else {
            $response = $this->request($region, '/observer-mode/rest/featured');
            if ($response->code === 200) {  // REQUEST SUCCESS
                $gameList = [];
                foreach ($response->body->gameList as $data) {
                    $teams = array();
                    // seperates the teams
                    foreach ($data->participants as $summoner) {
                        $teams[$summoner->teamId][] = $summoner;
                    }
                    $game = new Game($data->gameId, $data->gameMode, $data->gameType, $data->gameStartTime, $teams, $data->platformId);
                    // check platform id of the game to make sure it in the region
                    if ($this->platformList[$data->platformId] == $region) {
                        $gameList[] = $game;
                    }
                }
                $resp->json($gameList);
            } else {   // REQUEST FAIL
                $this->makeErrorResponse($resp, $resp->code);
            }
        }
    }
}
