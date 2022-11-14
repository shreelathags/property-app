<?php

namespace Tests\Feature\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\View;
use Tests\Feature\FeatureTestCase;

class PropertyControllerTest extends FeatureTestCase
{
    public function testSearch()
    {
        $propertyType = PropertyType::factory()->create();
        $property = Property::factory()->create(['property_type_id' => $propertyType->id]);

        $expected = View::make('property.list', ['properties' => new Collection([$property])])->render();

        $response = $this->get('/api/properties/search?text=' . $property->address . '&agentId=1');

        $response->assertStatus(200);

        $view = $response->original;

        $this->assertEquals($expected, $view);
    }
}
