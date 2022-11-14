<?php

namespace App\Repositories;

use App\Models\PropertyType;
use App\Repositories\Interfaces\PropertyTypeRepositoryInterface;

class PropertyTypeRepository implements PropertyTypeRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findAll()
    {
        return PropertyType::all();
    }

    /**
     * @inheritDoc
     */
    public function save(int $propertyTypeId, string $title, string $description)
    {
        return PropertyType::create(
            [
                'property_type_id' => $propertyTypeId,
                'title' => $title,
                'description' => $description,
            ]
        );
    }
}
