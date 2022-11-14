<?php

namespace Tests\Feature\Repositories;

use App\Models\PropertyUpdate;
use App\Repositories\PropertyUpdateRepository;
use Tests\Feature\FeatureTestCase;

class PropertyUpdateRepositoryTest extends FeatureTestCase
{
    private PropertyUpdateRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PropertyUpdateRepository();
    }

    public function testFindByUuids()
    {
        $propertyUpdate = PropertyUpdate::factory()->create();

        $result = $this->repository->findByUuids([$propertyUpdate->uuid]);

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertEquals($propertyUpdate->uuid, $result->first()->uuid);
    }

    public function testSave()
    {
        $data = PropertyUpdate::factory()->raw();

        $this->assertDatabaseMissing(
            'property_updates',
            [
                'uuid' => $data['uuid'],
                'property_updated_at' => $data['property_updated_at'],
            ]
        );

        $result = $this->repository->save($data['uuid'], $data['property_updated_at']);

        $this->assertInstanceOf(PropertyUpdate::class, $result);

        $this->assertDatabaseHas(
            'property_updates',
            [
                'uuid' => $data['uuid'],
                'property_updated_at' => $data['property_updated_at'],
            ]
        );
    }

    public function testUpdate()
    {
        $propertyUpdate = PropertyUpdate::factory()->create();

        $newUpdatedAt = now()->subDays(10)->format('Y-m-d H:i:s');

        $this->assertDatabaseMissing(
            'property_updates',
            [
                'uuid' => $propertyUpdate->uuid,
                'property_updated_at' => $newUpdatedAt,
            ]
        );

        $this->repository->update($propertyUpdate->uuid, $newUpdatedAt);

        $this->assertDatabaseHas(
            'property_updates',
            [
                'uuid' => $propertyUpdate->uuid,
                'property_updated_at' => $newUpdatedAt,
            ]
        );
    }
}
