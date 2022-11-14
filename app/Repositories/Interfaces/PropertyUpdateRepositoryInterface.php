<?php

namespace App\Repositories\Interfaces;

use App\Models\PropertyUpdate;

interface PropertyUpdateRepositoryInterface
{
    /**
     * @param array $uuids
     * @return PropertyUpdate[] |null
     */
    public function findByUuids(array $uuids);

    /**
     * @param string $uuid
     * @param string $updated_at
     * @return PropertyUpdate
     */
    public function save(string $uuid, string $updated_at);

    /**
     * @param string $uuid
     * @param string $updated_at
     * @return void
     */
    public function update(string $uuid, string $updated_at);
}
