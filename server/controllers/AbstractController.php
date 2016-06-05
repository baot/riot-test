<?php namespace Server\Controllers;

use Klein\Response;
use Klein\Exceptions\DispatchHaltedException;
use bandwidthThrottle\tokenBucket\BlockingConsumer as Consumer;
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
     * valid region for the controller
     * if empty, all regions can be used
     *
     * @var array
     */
    protected $validRegions = [];

    /*
     * http response error status and corresponded message
     *
     * @var array
     */
    protected $responseErrors = [
        "404" => "resource not found",
        "500" => "internal server error",
        "429" => "access "
    ];

    /*
     * versions available of the api
     *
     * @var array
     */
    protected $versions;

    /*
     * token bucket blockingConsumer
     *
     * @var bandwidthThrottle\tokenBucket\BlockingConsumer
     */
    protected $consumer;

    /*
     * default construct
     *
     * @param \Server\RiotClient $client
     * @param bandwidthThrottle\tokenBucket\BlockingConsumer $consumer
     * */
    public function __construct(RiotClient $client, Consumer $consumer)
    {
        $this->client = $client;
        $this->consumer = $consumer;
    }

    /*
     * making request to riot
     * NOTE: request to static-data riot api doesnot count in the RATE LIMIT
     *
     * @param string $region
     * @param bool $static
     * @param string $url
     * @param array $params
     * @return \Server\Response
     */
    public function request($region, $static, $url, $params = [])
    {
        // if not static we have to consume 1 bucket to prevent Rate limit
        if (!$static)
        {
            $this->consumer->consume(1);
        }
        $response = $this->client->request($region, $url, $params);
        return $response;
    }

    /*
     * Making error response with correspond status
     *
     * @param \Klein\Response
     * @param int $error_status
     */

    public function makeErrorResponse(Response $resp, $error_status)
    {
        //default error
        $message = "error";
        if (isset($this->responseErrors[$error_status]))
        {
            $message = $this->responseErrors[$error_status];
        }
        $resp->code($error_status);
        $resp->json(['error' => $message]);
        throw new DispatchHaltedException(null, DispatchHaltedException::SKIP_REMAINING);
    }
}

