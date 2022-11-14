<?php

namespace Database\Factories;

use App\Models\PropertyUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyUpdateFactory extends Factory
{
    protected $model = PropertyUpdate::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'property_updated_at' => now(),
        ];
    }
}
