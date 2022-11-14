<?php

namespace Database\Factories;

use App\Models\ApiRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApiRequestFactory extends Factory
{
    protected $model = ApiRequest::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomDigit(),
            'host' => $this->faker->url,
            'endpoint' => '/test',
            'method' => 'GET',
            'query' => json_encode([]),
            'body' => json_encode([]),
            'response_code' => 200,
            'response' => json_encode(
                [
                    "data" => [
                        [
                            "town" => $this->faker->city,
                            "type" => "sale",
                            "uuid" => $this->faker->uuid,
                            "price" => $this->faker->randomNumber(3),
                            "county" => "Pennsylvania",
                            "address" => $this->faker->address,
                            "country" => $this->faker->country,
                            "latitude" => $this->faker->latitude,
                            "longitude" => $this->faker->longitude,
                            "created_at" => now()->subMonth(),
                            "image_full" => $this->faker->url,
                            "updated_at" => now()->subMonth(),
                            "description" => $this->faker->text,
                            "num_bedrooms" => $this->faker->numberBetween(1,5),
                            "num_bathrooms" => $this->faker->numberBetween(1,5),
                            "property_type" => [
                                "id" => $this->faker->randomNumber(),
                                "title" => $this->faker->name,
                                "created_at" => now()->subYear(),
                                "updated_at" => null,
                                "description" => $this->faker->text
                            ],
                            "image_thumbnail" => $this->faker->url,
                            "property_type_id" => $this->faker->randomNumber()
                        ]
                    ]
                ]
            ),
        ];
    }
}
