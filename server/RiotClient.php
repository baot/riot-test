<?php 
namespace Server;

use Httpful\Request as HttpReq;

class RiotClient
{
    /*
     * Riot api_key client
     *
     * @var string
     */
    protected $api_key;

    /*
     * Riot api url
     *
     * @var string
     */
    protected $api_host;

    /*
     * constructor
     *
     * @param string $api_key
     * @param string $api_url
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
        $this->api_host = 'api.pvp.net';
    }
}
