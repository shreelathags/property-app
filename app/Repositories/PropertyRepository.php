<?php

namespace App\Repositories;

use App\Models\Property;
use App\Repositories\Interfaces\PropertyRepositoryInterface;

class PropertyRepository implements PropertyRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function save(array $data)
    {
        return Property::create(
            [
                "property_id" => $data["uuid"],
                "county" => $data["county"],
                "country" => $data["country"],
                "town" => $data["town"],
                "description" => $data["description"],
                "details_url" => $data["details_url"] ?? null,
                "address" => $data["address"],
                "image_url" => $data["image_thumbnail"],
                "thumbnail_url" => $data["image_full"],
                "latitude" => $data["latitude"],
                "longitude" => $data["longitude"],
                "no_of_bedrooms" => $data["num_bedrooms"],
                "no_of_bathrooms" => $data["num_bathrooms"],
                "price" => $data["price"],
                "for_sale" => $data["for_sale"],
                "for_rent" => $data["for_rent"],
                "property_type_id" => $data["property_type_id"],
                "property_created_at" => $data["created_at"],
                "property_updated_at" => $data["updated_at"],
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function update(array $data)
    {
        return Property::where("property_id", $data["uuid"])
            ->update(
                [
                    "property_id" => $data["uuid"],
                    "county" => $data["county"],
                    "country" => $data["country"],
                    "town" => $data["town"],
                    "description" => $data["description"],
                    "details_url" => $data["details_url"] ?? null,
                    "address" => $data["address"],
                    "image_url" => $data["image_thumbnail"],
                    "thumbnail_url" => $data["image_full"],
                    "latitude" => $data["latitude"],
                    "longitude" => $data["longitude"],
                    "no_of_bedrooms" => $data["num_bedrooms"],
                    "no_of_bathrooms" => $data["num_bathrooms"],
                    "price" => $data["price"],
                    "for_sale" => $data["for_sale"],
                    "for_rent" => $data["for_rent"],
                    "property_type_id" => $data["property_type_id"],
                    "property_created_at" => $data["created_at"],
                    "property_updated_at" => $data["updated_at"],
                ]
        );
    }

    /**
     * @inheritDoc
     */
    public function search(string $text, string $agentId)
    {
        return Property::where('address', 'like', '%' . $text . '%')
            ->whereDoesntHave('agents', function($q) use ($agentId) {
                $q->where('id', '=', $agentId);
            })
            ->with('property_type')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function findAgentForViewing(Property $property)
    {
        return $property->agents()->withPivotValue('view', 1)->get();
    }
}
