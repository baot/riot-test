<?php 
namespace Server\Controllers;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

class RegionController extends AbstractController
{
    /*
     * Get all supported riot api regions
     *
     * @param Klein\Request $req
     * @param Klein\Response $resp
     * @param Klein\ServiceProvider $service
     */
    public function getAllRegions(Request $req, Response $resp, ServiceProvider $service)
    {
        $region_arr = ["br", "eune", "euw", "jp", "kr", "lan", "las", "na", "oce", "tr", "ru", "pbe"];
        $resp->json($region_arr);
    }

}