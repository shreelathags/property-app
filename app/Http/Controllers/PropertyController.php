<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\PropertyRepositoryInterface;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    private PropertyRepositoryInterface $propertyRepository;

    public function __construct(PropertyRepositoryInterface $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    public function search(Request $request)
    {
        $searchText = $request->get('text');
        $agentId = $request->get('agentId');

        $properties = $this->propertyRepository->search($searchText, $agentId);

        return view('property.list', ['properties' => $properties]);
    }
}
