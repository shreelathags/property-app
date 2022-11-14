<?php

namespace App\Jobs;

use App\Services\ExternalRequest\ApiRequestServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetPropertyData implements ShouldQueue
{
    use Queueable;
    use Dispatchable;

    private const LIMIT = 100;

    private int $offset;

    public function __construct(int $offset = 0)
    {
        $this->offset = $offset;
    }

    public function handle(ApiRequestServiceInterface $apiRequestService)
    {
        $host = config('custom.property_api.host');
        $endpoint = config('custom.property_api.property_url');
        $params = [
            "query" => [
                "page[number]" => $this->offset,
                "page[size]" => self::LIMIT,
                "api_key" => config('custom.property_api.key')
            ]
        ];

        //Make the external request
        $apiRequest = $apiRequestService->call("GET", $host, $endpoint, [], $params);

        //Refresh the model to get the updated response
        $apiRequest->refresh();

        //Extract the data
        $response = (array) json_decode($apiRequest->response, true);
        $data = $response["data"];

        if (empty($data)) {
            return;
        }

        //Send it for processing
        ProcessPropertyData::dispatch($apiRequest->id);

        //Dispatch the next batch
        GetPropertyData::dispatch($this->offset + self::LIMIT);
    }
}
