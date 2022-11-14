<?php

namespace Tests\Unit\Jobs;

use App\Jobs\GetPropertyData;
use App\Jobs\ProcessPropertyData;
use App\Models\ApiRequest;
use App\Services\ExternalRequest\GuzzleApiRequestService;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Mockery\MockInterface;

class GetPropertyDataTest extends TestCase
{
    private MockInterface $apiRequestServiceMock;

    private MockInterface $apiRequestMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiRequestServiceMock = Mockery::mock(GuzzleApiRequestService::class);
        $this->apiRequestMock = Mockery::mock(ApiRequest::class);
    }

    public function testGetPropertyData()
    {
        Queue::fake();

        $apiResponse = ["data" => ['xyz']];

        $this->apiRequestServiceMock
            ->shouldReceive('call')
            ->andReturn($this->apiRequestMock);

        $this->apiRequestMock
            ->shouldReceive('refresh')
            ->andReturn();

        $this->apiRequestMock
            ->shouldReceive('getAttribute')
            ->with('response')
            ->andReturn(json_encode($apiResponse));

        $this->apiRequestMock
            ->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(rand());

        $job = new GetPropertyData(0);
        $job->handle($this->apiRequestServiceMock);

        Queue::assertPushed(ProcessPropertyData::class);
        Queue::assertPushed(GetPropertyData::class);
    }

    public function testGetPropertyDataEmptyResponseFromApi()
    {
        Queue::fake();

        $apiResponse = ["data" => []];

        $this->apiRequestServiceMock
            ->shouldReceive('call')
            ->andReturn($this->apiRequestMock);

        $this->apiRequestMock
            ->shouldReceive('refresh')
            ->andReturn();

        $this->apiRequestMock
            ->shouldReceive('getAttribute')
            ->with('response')
            ->andReturn(json_encode($apiResponse));

        $job = new GetPropertyData(0);
        $job->handle($this->apiRequestServiceMock);

        Queue::assertNothingPushed();
    }
}
