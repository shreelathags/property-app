<?php

namespace Tests\Feature\Repositories;

use App\Models\ApiRequest;
use App\Repositories\ApiRequestRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Tests\Feature\FeatureTestCase;

class ApiRequestRepositoryTest extends FeatureTestCase
{
    protected ApiRequestRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ApiRequestRepository();
    }

    public function testSave()
    {
        $data = ApiRequest::factory()->raw();

        $this->assertDatabaseMissing(
            'api_requests',
            [
                'host' => $data['host'],
                'endpoint' => $data['endpoint'],
                'method' => $data['method'],
                'query' => $data['query'],
                'body' => $data['body'],
            ]
        );

        $result = $this->repository->save($data['method'], $data['host'], $data['endpoint'], json_decode($data['query']), json_decode($data['body']));

        $this->assertInstanceOf(ApiRequest::class, $result);

        $this->assertDatabaseHas(
            'api_requests',
            [
                'host' => $data['host'],
                'endpoint' => $data['endpoint'],
                'method' => $data['method'],
                'query' => $data['query'],
                'body' => $data['body'],
            ]
        );
    }

    public function testUpdateResponse()
    {
        $apiRequest = ApiRequest::factory()->create();
        $responseCode = 500;
        $response = json_encode(['something wrong']);

        $this->repository->updateResponse($apiRequest->id, $responseCode, $response);

        $this->assertDatabaseHas(
            'api_requests',
            [
                'id' => $apiRequest->id,
                'response_code' => $responseCode,
                'response' => $response,
            ]
        );
    }

    public function testFindOrFailFinds()
    {
        $apiRequest = ApiRequest::factory()->create();

        $result = $this->repository->findOrFail($apiRequest->id);

        $this->assertInstanceOf(ApiRequest::class, $result);
    }

    public function testFindOrFailFails()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository->findOrFail(Str::uuid()->toString());
    }
}
