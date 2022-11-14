<?php

namespace App\Repositories;

use App\Models\PropertyUpdate;
use App\Repositories\Interfaces\PropertyUpdateRepositoryInterface;

class PropertyUpdateRepository implements PropertyUpdateRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findByUuids(array $uuids)
    {
        return PropertyUpdate::whereIn("uuid", $uuids)->get();
    }

    /**
     * @inheritDoc
     */
    public function save(string $uuid, string $updated_at)
    {
        return PropertyUpdate::create(
            [
                "uuid" => $uuid,
                "property_updated_at" => $updated_at,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function update(string $uuid, string $updated_at)
    {
        PropertyUpdate::where("uuid", $uuid)
            ->update(
                [
                    "property_updated_at" => $updated_at
                ]
            );
    }
}
