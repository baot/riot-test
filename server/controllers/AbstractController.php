<?php 
namespace Server\Controllers;

use Klein\Response;

use Server\RiotClient as RiotClient;


abstract class AbstractController
{

    /*
     * client to be used by the controller
     *
     * @var \Server\RiotClient
     */
    protected $client;

    /*
     * http response error status and corresponded message
     *
     * @var array
     */
    protected $responseErrors = [
        '404' => 'resource not found',
        '429' => 'limit access',
        '500' => 'internal server error'
    ];

    /*
     * default construct
     *
     * @param \Server\RiotClient $client
     * @param bandwidthThrottle\tokenBucket\BlockingConsumer $consumer
     * */
    public function __construct(RiotClient $client)
    {
        $this->client = $client;
    }

    /*
     * making request to riot
     *
     * @param string $region
     * @param string $url
     * @param array $params
     * @return \Server\Response
     */
    public function request($region, $url, $params = [])
    {
        $response = $this->client->request($region, $url, $params);
        return $response;
    }

    /*
     * Making error response with correspond status
     *
     * @param \Klein\Response
     * @param int $error_status
     */

    public function make_error_response(Response $resp, $error_status)
    {
        //default error
        $message = "error";
        if (isset($this->responseErrors[$error_status]))
        {
            $message = $this->responseErrors[$error_status];
        }
        $resp->code($error_status);
        $resp->json(['error' => $message]);
    }
}

