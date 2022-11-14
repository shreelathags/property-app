<?php

namespace App\Repositories;

use App\Models\Agent;
use App\Models\Property;
use App\Repositories\Interfaces\AgentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AgentRepository implements AgentRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function save(string $firstName, string $lastName, string $email, string $phone, string $address)
    {
        return Agent::create(
            [
                "first_name" => $firstName,
                "last_name" => $lastName,
                "email" => $email,
                "phone" => $phone,
                "address" => $address,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function search(string $text)
    {
        return Agent::where('first_name', 'like', '%' . $text . '%')
            ->orWhere('last_name', 'like', '%' . $text . '%')
            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $text . '%')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getProperties(Agent $agent)
    {
        return $agent->properties()->with('property_type')->get();
    }

    /**
     * @inheritDoc
     */
    public function addProperty(Agent $agent, Property $property, bool $sellAgent)
    {
        $viewAgent = $sellAgent ? false : true;

        $agent->properties()->attach($property->id, ['view' => $viewAgent, 'sell' => $sellAgent]);

        return $agent;
    }

    /**
     * @inheritDoc
     */
    public function summary(int $offset, int $limit)
    {
        //Query to evaluate the total price for agent's properties
        $subQuery = DB::table('agents_properties AS ap')
            ->select('ap.agent_id', DB::raw("SUM(p.price) as 'Total Price'"))
            ->join('properties AS p', 'ap.property_id', '=', 'p.id')
            ->groupBy(['ap.agent_id'])
            ;
        $subSql = $subQuery->toSql();

        //Main query to extract agent details, property details and the total price
        return DB::table('agents AS a')
            ->select(DB::raw("CONCAT(a.first_name, ' ', a.last_name) AS 'Agent Name'"),
                     DB::raw("GROUP_CONCAT(CONCAT('\'', p.address, '\'')  SEPARATOR ', ') AS Properties"),
                     "Total Price"
            )
            ->join('agents_properties AS ap', 'ap.agent_id', '=', 'a.id')
            ->join('properties AS p', 'ap.property_id', '=', 'p.id')
            ->join(
                DB::raw('(' . $subSql. ') AS subWithPrice'),
                function($join) {
                    $join->on('a.id', '=', 'subWithPrice.agent_id');
                }
            )
            ->groupBy(['a.id'])
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
}
