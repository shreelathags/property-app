<?php

namespace Tests\Feature\Repositories;

use App\Models\PropertyType;
use App\Repositories\PropertyTypeRepository;
use Tests\Feature\FeatureTestCase;

class PropertyTypeRepositoryTest extends FeatureTestCase
{
    protected PropertyTypeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PropertyTypeRepository();
    }

    public function testFindAll()
    {
        $propertyType = PropertyType::factory()->create();

        $result = $this->repository->findAll();

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertEquals($propertyType->id, $result->first()->id);
    }

    public function testSave()
    {
        $data = PropertyType::factory()->raw();

        $this->assertDatabaseMissing(
            'property_types',
            [
                'property_type_id' => $data['property_type_id'],
                'title' => $data['title'],
                'description' => $data['description'],
            ]
        );

        $result = $this->repository->save($data['property_type_id'], $data['title'], $data['description']);

        $this->assertInstanceOf(PropertyType::class, $result);

        $this->assertDatabaseHas(
            'property_types',
            [
                'property_type_id' => $data['property_type_id'],
                'title' => $data['title'],
                'description' => $data['description'],
            ]
        );
    }
}
