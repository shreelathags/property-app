<?php

namespace App\Repositories\Interfaces;

use App\Models\PropertyType;

interface PropertyTypeRepositoryInterface
{
    /**
     * @return PropertyType[]
     */
    public function findAll();

    /**
     * @param int $propertyTypeId
     * @param string $title
     * @param string $description
     * @return PropertyType
     */
    public function save(int $propertyTypeId, string $title, string $description);
}
