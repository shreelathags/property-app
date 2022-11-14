<?php

namespace Tests\Feature\Controllers;

use App\Models\Agent;
use App\Models\Property;
use App\Models\PropertyType;
use App\Repositories\AgentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\View;
use Mockery;
use Tests\Feature\FeatureTestCase;

class AgentControllerTest extends FeatureTestCase
{
    public function testCreate()
    {
        $data = [
            "data" => [
                "firstName" => 'John',
                'lastName' => 'Doe',
                'email' => 'test@abc.com',
                'phone' => '1234567890',
                'address' => '24, abc street',
            ]
        ];

        $response = $this->post('/api/agents', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas(
            'agents',
            [
                "first_name" => 'John',
                'last_name' => 'Doe',
                'email' => 'test@abc.com',
                'phone' => '1234567890',
                'address' => '24, abc street',
            ]
        );
    }

    public function testGet()
    {
        $propertyType = PropertyType::factory()->create();
        $property = Property::factory()->create(['property_type_id' => $propertyType->id]);
        $agent = Agent::factory()->hasAttached($property, ['view' => true, 'sell' => false])->create();

        $agentRepository = new AgentRepository();
        $properties = $agentRepository->getProperties($agent);
        $expected = View::make('agent.view', ['agent' => $agent, 'properties' => $properties])->render();

        $response = $this->get('/api/agents/' . $agent->id);

        $view = $response->original;

        $this->assertEquals($expected, $view);
    }

    public function testSearch()
    {
        $agent = Agent::factory()->make();
        $agentRepositoryMock = $this->instance(AgentRepository::class, Mockery::mock(AgentRepository::class));
        $agentRepositoryMock->shouldReceive('search')->andReturn(new Collection([$agent]));

        $expected = View::make('agent.list', ['agents' => new Collection([$agent])])->render();

        $response = $this->get('/api/agents/search?text=' . $agent->first_name);

        $view = $response->original;

        $this->assertEquals($expected, $view);
    }

    public function testAddProperty()
    {
        $agent = Agent::factory()->create();
        $propertyType = PropertyType::factory()->create();
        $property = Property::factory()->create(['property_type_id' => $propertyType->id]);

        $expected = [
            "id" => $agent->id,
            "first_name" => $agent->first_name,
            "last_name" => $agent->last_name,
            "email" => $agent->email,
            "phone" => $agent->phone,
            "address" => $agent->address,
            "created_at" => $agent->created_at->format('Y-m-d\TH:i:s') . '.000000Z',
            "updated_at" => $agent->updated_at->format('Y-m-d\TH:i:s') . '.000000Z',
        ];
        $response = $this->post('/api/agents/' . $agent->id . '/properties/' . $property->id, ['sellAgent' => true]);

        $response->assertStatus(200);

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($expected, $actual);
    }

    public function testSummary()
    {
        $property1 = Property::factory()->make();
        $property2 = Property::factory()->make();
        $collection = new Collection([$property1, $property2]);
        $agent = Agent::factory()->hasAttached($collection, ['view' => true, 'sell' => false])->make();

        $expected = [
            [
                "Agent Name" => $agent->first_name . " " . $agent->last_name,
                "Properties" => "'" . $property1->address . "', '" . $property2->address ."'",
                "Total Price" => $property1->price + $property2->price,
            ]
        ];

        $agentRepositoryMock = $this->instance(AgentRepository::class, Mockery::mock(AgentRepository::class));
        $agentRepositoryMock->shouldReceive('summary')->andReturn($expected);

        $response = $this->get('/api/agents/summary?page[offset]=0&page[limit]=100');

        $response->assertStatus(200);

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($expected, $actual);
    }
}
