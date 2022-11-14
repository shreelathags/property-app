<?php

namespace Tests\Unit\Jobs;

use App\Jobs\ProcessPropertyData;
use App\Models\ApiRequest;
use App\Models\PropertyType;
use App\Models\PropertyUpdate;
use App\Repositories\ApiRequestRepository;
use App\Repositories\PropertyRepository;
use App\Repositories\PropertyTypeRepository;
use App\Repositories\PropertyUpdateRepository;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;

class ProcessPropertyDataTest extends TestCase
{
    private MockInterface $apiRequestRepositoryMock;

    private MockInterface $propertyRepositoryMock;

    private MockInterface $propertyUpdateRepositoryMock;

    private MockInterface $propertyTypeRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiRequestRepositoryMock = Mockery::mock(ApiRequestRepository::class);
        $this->propertyRepositoryMock = Mockery::mock(PropertyRepository::class);
        $this->propertyUpdateRepositoryMock = Mockery::mock(PropertyUpdateRepository::class);
        $this->propertyTypeRepositoryMock = Mockery::mock(PropertyTypeRepository::class);
    }

    public function testProcessPropertyDataInserts()
    {
        $apiRequest = ApiRequest::factory()->make();
        $this->apiRequestRepositoryMock
            ->shouldReceive('findOrFail')
            ->andReturn($apiRequest)
            ->once();

        $this->propertyUpdateRepositoryMock
            ->shouldReceive('findByUuids')
            ->andReturn(new Collection())
            ->once();

        $this->propertyTypeRepositoryMock
            ->shouldReceive('findAll')
            ->andReturn(new Collection())
            ->once();

        $propertyType = PropertyType::factory()->make();
        $this->propertyTypeRepositoryMock
            ->shouldReceive('save')
            ->andReturn($propertyType)
            ->once();

        DB::shouldReceive('transaction')->once();

        $job = new ProcessPropertyData($apiRequest->id);
        $job->handle($this->apiRequestRepositoryMock, $this->propertyRepositoryMock, $this->propertyUpdateRepositoryMock, $this->propertyTypeRepositoryMock);
    }

    public function testProcessPropertyDataInsertsAndUpdates()
    {
        $apiRequest = ApiRequest::factory()->make();
        $this->apiRequestRepositoryMock
            ->shouldReceive('findOrFail')
            ->andReturn($apiRequest)
            ->once();

        $propertyUpdate = PropertyUpdate::factory()->make();
        $this->propertyUpdateRepositoryMock
            ->shouldReceive('findByUuids')
            ->andReturn(new Collection([$propertyUpdate]))
            ->once();

        $this->propertyTypeRepositoryMock
            ->shouldReceive('findAll')
            ->andReturn(new Collection())
            ->once();

        $propertyType = PropertyType::factory()->make();
        $this->propertyTypeRepositoryMock
            ->shouldReceive('save')
            ->andReturn($propertyType)
            ->once();

        DB::shouldReceive('transaction')->twice();

        $job = new ProcessPropertyData($apiRequest->id);
        $job->handle($this->apiRequestRepositoryMock, $this->propertyRepositoryMock, $this->propertyUpdateRepositoryMock, $this->propertyTypeRepositoryMock);
    }
}
