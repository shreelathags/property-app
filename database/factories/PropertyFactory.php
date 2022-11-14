<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomDigit(),
            'property_id' => $this->faker->uuid,
            'county' => $this->faker->name,
            'country' => $this->faker->country,
            'town' => $this->faker->city,
            'description' => $this->faker->text,
            'details_url' => $this->faker->url,
            'address' => $this->faker->address,
            'image_url' => $this->faker->url,
            'thumbnail_url' => $this->faker->url,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'no_of_bedrooms' => $this->faker->numberBetween(1, 5),
            'no_of_bathrooms' => $this->faker->numberBetween(1, 5),
            'price' => $this->faker->randomNumber(4),
            'for_sale' => $this->faker->boolean,
            'for_rent' => $this->faker->boolean,
            'property_type_id' => PropertyType::factory()->make(),
            'property_created_at' => now()->subMonth(),
            'property_updated_at' => now()->subMonth(),
        ];
    }
}
