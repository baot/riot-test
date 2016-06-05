<?php 
namespace Server\Controllers;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

use Server\Models\Summoner;

//TODO: getIdOfSummoner and get RecordsOfSummoner using different version of api
class SummonerController extends AbstractController
{
    /*
     * available versions used by summoner riot api
     *
     * @var array
     */
    protected $versions = [
        '1.3' => 'v1.3',
        '1.4' => 'v1.4'
    ];

    /*
     * Get Id of summoner by summoner's name
     *
     * @param string $name
     * @param string $location
     * @return Server\Models\Summoner|null
     */
    public function getIdOfSummonerByName($name, $location)
    {
        $path = '/api/lol/' . $location . '/' . $this->versions['1.4'] . '/summoner/by-name/' . $name;
        $name = strtolower($name);
        $name  = preg_replace('/\s+/', '', $name);
        $respond = $this->request($location, $path);
        if ($respond->code === 200) {
            $summoner = new Summoner($respond->body->$name->id, $name, []);
            return $summoner;
        } else {
            return null;
        }
    }

    public function getRecordsOfSummoner(Request $req, Response $resp, ServiceProvider $service)
    {
        $name = $req->paramsNamed()->get('summonerName', null);
        $name = preg_replace('/\s+/', '', $name);
        $location = strtolower($req->paramsNamed()->get('region', null));

        //checking params
        if (is_null($name) || is_null($location)) {
            $this->makeErrorResponse($resp, 404);
        } else {
            $summoner = $this->getIdOfSummonerByName($name, $location);

            if (is_null($summoner)) {
                // TODO: error response 
                $this->makeErrorResponse($resp, 404);
            } else {
                // TODO: refactor the url path methods
                $path = '/api/lol/'. $location . '/' . $this->versions['1.3'] . '/game/by-summoner/'. $summoner->id . '/recent';
                $respond = $this->request($location, $path);
                if ($respond->code === 200) {   // REQUEST SUCCESS
                    $record = array();
                    // list of 7 recent matches or all matches of summoner (if matches <= 7)
                    $matches = array_slice($respond->body->games, 0, 7);
                    foreach ($matches as $match) {
                        $record[] = ($match->stats->win ? 'win' : 'lose');
                    }
                    $summoner->record = $record;    
                    $resp->json($summoner);
                } else {   // REQUEST FAIL
                    $this->makeErrorResponse($resp, 404);
                }
            }
        }
    }
}
