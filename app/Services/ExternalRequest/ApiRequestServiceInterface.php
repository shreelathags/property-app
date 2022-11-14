<?php

namespace App\Services\ExternalRequest;

interface ApiRequestServiceInterface
{
    public function call($method, $host, $url, $headers, $params, $body = null);
}
