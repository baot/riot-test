<?php 
namespace Server\Controllers;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

use Server\Models\Champion;

class ChampionController extends AbstractController
{

    /*
     * Get champion by id and location
     *
     * @param Klein\Request $req
     * @param Klein\Response $resp,
     * @param Klein\ServiceProvider $service
     */

    public function getChampionById(Request $req, Response $resp, ServiceProvider $service)
    {
        $championId = $req->paramsNamed()->get('championId', null);
        $location = strtolower($req->paramsNamed()->get('region', null));

        // checking params
        if (is_null($championId) || is_null($location)) {
            $this->makeErrorResponse($resp, 404);
        } else {
            $path = "/api/lol/static-data/" . $location . "/v1.2/champion/" . $championId;
            // global is passed bc of static-data
            $respond = $this->request("global", $path, ["champData" => "all"]);
            if ($respond->code === 200) {
                $spellIcons = array();
                foreach ($respond->body->spells as $spell) {
                    $spellIcons[] = $spell->image;
                }
                $avatar = $respond->body->image;
                $champion = new Champion($avatar, $spellIcons);

                $resp->json($champion);
            } else {
                $this->makeErrorResponse($resp, 404);
            }
        }
    }
}
