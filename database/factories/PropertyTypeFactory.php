<?php

namespace Database\Factories;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyTypeFactory extends Factory
{
    protected $model = PropertyType::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomDigit(),
            'property_type_id' => $this->faker->randomDigit(),
            'title' => $this->faker->name,
            'description' => $this->faker->text,
        ];
    }
}
