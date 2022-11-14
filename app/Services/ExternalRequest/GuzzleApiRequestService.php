<?php

namespace App\Services\ExternalRequest;

use App\Repositories\Interfaces\ApiRequestRepositoryInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleApiRequestService implements ApiRequestServiceInterface
{
    private Client $client;

    private ApiRequestRepositoryInterface $apiRequestRepository;

    public function __construct(ApiRequestRepositoryInterface $apiRequestRepository)
    {
        $this->apiRequestRepository = $apiRequestRepository;
    }

    /**
     * @throws Exception
     */
    public function call($method, $host, $endpoint, $headers, $params = [], $body = [])
    {
        $this->client = new Client(
            [
                "base_uri" => config('custom.property_api.host'),
                "headers" => $headers,
            ]
        );

        switch($method) {
            case "GET":
                return $this->get($host, $endpoint, $params);
            default:
                throw new Exception("This method is not implemented yet");
        }
    }

    /**
     * @param $host
     * @param $endpoint
     * @param $params
     * @return mixed
     * @throws GuzzleException
     */
    protected function get($host, $endpoint, $params = [])
    {
        //Save this a new api request
        $apiRequest = $this->apiRequestRepository->save('GET', $host, $endpoint, $params);

        try {
            $response = $this->client->get($endpoint, $params);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            //Save the response
            $this->apiRequestRepository->updateResponse($apiRequest->id, $response->getStatusCode(), $response->getBody()->getContents());
        }

        //Save the response
        $data = $response->getBody()->getContents();
        $this->apiRequestRepository->updateResponse($apiRequest->id, $response->getStatusCode(), $data);

        //Return api request
        return $apiRequest;
    }
}
