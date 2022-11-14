<?php

namespace Tests\Feature\Repositories;

use App\Models\Agent;
use App\Models\Property;
use App\Models\PropertyType;
use App\Repositories\PropertyRepository;
use Illuminate\Support\Str;
use Tests\Feature\FeatureTestCase;

class PropertyRepositoryTest extends FeatureTestCase
{
    private PropertyRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PropertyRepository();
    }

    public function testSave()
    {
        $data = Property::factory()->raw();
        $propertyType = PropertyType::factory()->create();

        $data['uuid'] = Str::uuid()->toString();
        $data["property_type_id"] = $propertyType->id;
        $data['image_full'] = $data['image_url'];
        $data['image_thumbnail'] = $data['thumbnail_url'];
        $data['num_bedrooms'] = $data['no_of_bedrooms'];
        $data['num_bathrooms'] = $data['no_of_bathrooms'];
        $data['created_at'] = $data['property_created_at'];
        $data['updated_at'] = $data['property_updated_at'];

        $dataInDB = [
            "property_id" => $data["uuid"],
            "county" => $data["county"],
            "country" => $data["country"],
            "town" => $data["town"],
            "description" => $data["description"],
            "details_url" => $data["details_url"] ?? null,
            "address" => $data["address"],
            "image_url" => $data["image_thumbnail"],
            "thumbnail_url" => $data["image_full"],
            "latitude" => $data["latitude"],
            "longitude" => $data["longitude"],
            "no_of_bedrooms" => $data["num_bedrooms"],
            "no_of_bathrooms" => $data["num_bathrooms"],
            "price" => $data["price"],
            "for_sale" => $data["for_sale"],
            "for_rent" => $data["for_rent"],
            "property_type_id" => $propertyType->id,
            "property_created_at" => $data["created_at"],
            "property_updated_at" => $data["updated_at"],
        ];

        $this->assertDatabaseMissing('properties', $dataInDB);

        $result = $this->repository->save($data);

        $this->assertInstanceOf(Property::class, $result);

        $this->assertDatabaseHas('properties', $dataInDB);
    }

    public function testUpdate()
    {
        $data = Property::factory()->raw();

        $data['uuid'] = Str::uuid()->toString();
        $data['image_full'] = $data['image_url'];
        $data['image_thumbnail'] = $data['thumbnail_url'];
        $data['num_bedrooms'] = $data['no_of_bedrooms'];
        $data['num_bathrooms'] = $data['no_of_bathrooms'];
        $data['created_at'] = $data['property_created_at'];
        $data['updated_at'] = $data['property_updated_at'];

        $propertyType = PropertyType::factory()->create();
        $property = Property::factory()->create(['property_id' => $data['uuid'], 'property_type_id' => $propertyType->id]);

        $data["property_type_id"] = $propertyType->id;

        $dataInDb = [
            "id" => $property->id,
            "property_id" => $data["uuid"],
            "county" => $data["county"],
            "country" => $data["country"],
            "town" => $data["town"],
            "description" => $data["description"],
            "details_url" => $data["details_url"] ?? null,
            "address" => $data["address"],
            "image_url" => $data["image_thumbnail"],
            "thumbnail_url" => $data["image_full"],
            "latitude" => $data["latitude"],
            "longitude" => $data["longitude"],
            "no_of_bedrooms" => $data["num_bedrooms"],
            "no_of_bathrooms" => $data["num_bathrooms"],
            "price" => $data["price"],
            "for_sale" => $data["for_sale"],
            "for_rent" => $data["for_rent"],
            "property_type_id" => $propertyType->id,
            "property_created_at" => $data["created_at"],
            "property_updated_at" => $data["updated_at"],
        ];

        $this->assertDatabaseMissing('properties', $dataInDb);

        $this->repository->update($data);

        $this->assertDatabaseHas('properties', $dataInDb);
    }

    public function testSearch()
    {
        $propertyType = PropertyType::factory()->create();
        $property = Property::factory()->create(['property_type_id' => $propertyType->id]);

        $result = $this->repository->search($property->address, Str::uuid()->toString());

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertEquals($property->id, $result->first()->id);
    }

    public function testFindAgentsForViewing()
    {
        $agent = Agent::factory()->create();
        $propertyType = PropertyType::factory()->create();
        $property = Property::factory()
            ->hasAttached([$agent], ['view' => true, 'sell' => false])
            ->create(['property_type_id' => $propertyType->id]);

        $result = $this->repository->findAgentForViewing($property);

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertEquals($agent->id, $result->first()->id);
    }
}
