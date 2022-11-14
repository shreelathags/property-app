<?php

namespace Tests\Unit\Services\ExternalService;

use App\Models\ApiRequest;
use App\Repositories\ApiRequestRepository;
use App\Services\ExternalRequest\GuzzleApiRequestService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use \Mockery;

class GuzzleApiRequestServiceTest extends TestCase
{
    public function testCallWithGet()
    {
        $apiRequestRepositoryMock = Mockery::mock(ApiRequestRepository::class);
        $clientMock = Mockery::mock('overload:' . Client::class)->makePartial();

        $apiRequestMock = ApiRequest::factory()->make();
        $apiRequestRepositoryMock
            ->shouldReceive('save')
            ->andReturn($apiRequestMock)
            ->once();

        $response = new Response();

        $clientMock
            ->shouldReceive('get')
            ->andReturn($response);

        $apiRequestRepositoryMock
            ->shouldReceive('updateResponse')
            ->once();

        $service = new GuzzleApiRequestService($apiRequestRepositoryMock);
        $service->call('GET', 'https://xyz.xom', '/', []);
    }
}
