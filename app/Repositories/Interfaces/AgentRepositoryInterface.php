<?php

namespace App\Repositories\Interfaces;

use App\Models\Agent;
use App\Models\Property;

interface AgentRepositoryInterface
{
    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $phone
     * @param string $address
     * @return Agent
     */
    public function save(string $firstName, string $lastName, string $email, string $phone, string $address);

    /**
     * @param string $text
     * @return Agent[]
     */
    public function search(string $text);

    /**
     * @param Agent $agent
     * @return Property[]
     */
    public function getProperties(Agent $agent);

    /**
     * @param Property $property
     * @return Agent
     */
    public function addProperty(Agent $agent, Property $property, bool $sellAgent);

    /**
     * @return array
     */
    public function summary(int $offset, int $limit);
}
