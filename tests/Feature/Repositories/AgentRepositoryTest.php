<?php

namespace Tests\Feature\Repositories;

use App\Models\Agent;
use App\Models\Property;
use App\Models\PropertyType;
use App\Repositories\AgentRepository;
use Tests\Feature\FeatureTestCase;

/**
 * search() and summary() methods are not tested because of the presence of MySQL keywords such as CONCAT in them.
 * SQLite does not support such keywords.
 */
class AgentRepositoryTest extends FeatureTestCase
{
    private AgentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new AgentRepository();
    }

    public function testSave()
    {
        $data = Agent::factory()->raw();

        $this->assertDatabaseMissing(
            'agents',
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]
        );

        $result = $this->repository->save($data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['address']);

        $this->assertInstanceOf(Agent::class, $result);

        $this->assertDatabaseHas(
            'agents',
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]
        );
    }

    public function testGetProperties()
    {
        $propertyType = PropertyType::factory()->create();
        $properties = Property::factory()->count(2)->create(['property_type_id' => $propertyType->id]);

        $agent = Agent::factory()->hasAttached($properties, ['view' => true, 'sell' => false])->create();

        $result = $this->repository->getProperties($agent);

        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }

    public function testAddProperty()
    {
        $propertyType = PropertyType::factory()->create();
        $property = Property::factory()->create(['property_type_id' => $propertyType->id]);

        $agent = Agent::factory()->create();

        $this->assertDatabaseMissing(
            'agents_properties',
            [
                'agent_id' => $agent->id,
                'property_id' => $property->id,
                'sell' => 0,
                'view' => 1,
            ]
        );

        $result = $this->repository->addProperty($agent, $property, false);

        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Agent::class, $result);

        $this->assertDatabaseHas(
            'agents_properties',
            [
                'agent_id' => $agent->id,
                'property_id' => $property->id,
                'sell' => 0,
                'view' => 1,
            ]
        );
    }
}
