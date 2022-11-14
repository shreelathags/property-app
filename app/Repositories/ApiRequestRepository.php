<?php

namespace App\Repositories;

use App\Models\ApiRequest;
use App\Repositories\Interfaces\ApiRequestRepositoryInterface;

class ApiRequestRepository implements ApiRequestRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function save($method, $host, $endpoint, $params = [], $body = [])
    {
        return ApiRequest::create(
            [
                'host' => $host,
                'endpoint' => $endpoint,
                'method' => $method,
                'query' => json_encode($params),
                'body' => json_encode($body),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function updateResponse(int $id, int $responseCode = null, string $response = '')
    {
        return ApiRequest::where('id', $id)
            ->update(
                [
                     'response_code' => $responseCode,
                     'response' => $response,
                ]
        );
    }

    /**
     * @inheritDoc
     */
    public function findOrFail($id)
    {
        return ApiRequest::findOrFail($id);
    }
}
