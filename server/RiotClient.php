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

    /*
     * Do a request to riot api
     *
     * @param region $region
     * @param string $path
     * @param array $params
     * @return \Server\Response
     */
    public function request($region, $path, array $params = [])
    {
        $params["api_key"] = $this->api_key;
        $url = 'https://' . $region . '.' . $this->api_host . $path . '?' . http_build_query($params);
        error_log(print_r($url, TRUE));
        $response = HttpReq::get($url)->send();
        return $response;
    }
}
