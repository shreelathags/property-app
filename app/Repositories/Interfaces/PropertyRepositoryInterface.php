<?php

namespace App\Repositories\Interfaces;

use App\Models\Agent;
use App\Models\Property;

interface PropertyRepositoryInterface
{
    /**
     * @param array $data
     * @return Property
     */
    public function save(array $data);

    /**
     * @param array $data
     * @return void
     */
    public function update(array $data);

    /**
     * @param string $text
     * @return Property[]
     */
    public function search(string $text, string $agentId);

    /**
     * @param Property $property
     * @return Agent[]
     */
    public function findAgentForViewing(Property $property);
}
