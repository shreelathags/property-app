<?php

namespace App\Repositories\Interfaces;

use App\Models\ApiRequest;

interface ApiRequestRepositoryInterface
{
    /**
     * @param $method
     * @param $host
     * @param $endpoint
     * @param $params
     * @param $body
     * @return ApiRequest
     */
    public function save($method, $host, $endpoint, $params = [], $body = []);

    /**
     * @param int $id
     * @param int $responseCode
     * @param string $response
     * @return mixed
     */
    public function updateResponse(int $id, int $responseCode = null, string $response = '');

    /**
     * @param $id
     * @return ApiRequest
     */
    public function findOrFail($id);
}
