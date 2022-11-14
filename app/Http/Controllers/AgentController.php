<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgentSummaryRequest;
use App\Http\Requests\CreateAgentRequest;
use App\Models\Agent;
use App\Models\Property;
use App\Repositories\Interfaces\AgentRepositoryInterface;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class AgentController extends Controller
{
    private AgentRepositoryInterface $agentRepository;

    private PropertyRepositoryInterface $propertyRepository;

    public function __construct(AgentRepositoryInterface $agentRepository, PropertyRepositoryInterface $propertyRepository)
    {
        $this->agentRepository = $agentRepository;
        $this->propertyRepository = $propertyRepository;
    }

    public function create(CreateAgentRequest $request)
    {
        $request = $request->all();

        $agent = $this->agentRepository->save(
            $request["data"]["firstName"],
            $request["data"]["lastName"],
            $request["data"]["email"],
            $request["data"]["phone"],
            $request["data"]["address"]
        );

        return response($agent);
    }

    public function get(Agent $agent)
    {
        $properties = $this->agentRepository->getProperties($agent);
        return view('agent.view', ['agent' => $agent, 'properties' => $properties]);
    }

    public function search(Request $request)
    {
        $searchText = $request->get('text');

        $agents = $this->agentRepository->search($searchText);

        return view('agent.list', ['agents' => $agents]);
    }

    /**
     * @throws ValidationException
     */
    public function addProperty(Agent $agent, Property $property, Request $request)
    {
        $sellAgent = $request->get('sellAgent');
        $sellAgent = filter_var($sellAgent, FILTER_VALIDATE_BOOLEAN);

        /** @var Collection $agents */
        $agents = $this->propertyRepository->findAgentForViewing($property);

        if ($agents->isNotEmpty()) {
            throw ValidationException::withMessages(['property' => 'This property already has an agent for viewing']);
        }

        $agent = $this->agentRepository->addProperty($agent, $property, $sellAgent);

        return response($agent);
    }

    public function summary(AgentSummaryRequest $request)
    {
        $page = $request->get('page');

        $offset = $page['offset'];
        $limit = $page['limit'];

        $summary = $this->agentRepository->summary($offset, $limit);

        return response($summary);
    }
}
