<?php

namespace App\Jobs;

use App\Enums\PropertyTypeEnum;
use App\Repositories\Interfaces\ApiRequestRepositoryInterface;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use App\Repositories\Interfaces\PropertyTypeRepositoryInterface;
use App\Repositories\Interfaces\PropertyUpdateRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class ProcessPropertyData implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    private int $apiRequestId;

    public function __construct(int $apiRequestId)
    {
        $this->apiRequestId = $apiRequestId;
    }

    public function handle(
        ApiRequestRepositoryInterface $apiRequestRepository,
        PropertyRepositoryInterface $propertyRepository,
        PropertyUpdateRepositoryInterface $propertyUpdateRepository,
        PropertyTypeRepositoryInterface $propertyTypeRepository
    ) {
        $apiRequest = $apiRequestRepository->findOrFail($this->apiRequestId);

        //Extract the data to process
        $response = (array) json_decode($apiRequest->response, true);
        $data = $response["data"];

        if (empty($data)) {
            return;
        }

        //Extract the uuids from the data set
        $allUuids = array_map(
            function($property) {
                return $property["uuid"];
            },
           $data
        );

        //Extract the existing uuids from the database
        $existingPropertiesWithUpdates = $propertyUpdateRepository->findByUuids($allUuids)->all();

        $existingUuids = array_map(
            function($propertyWithUpdate) {
                return $propertyWithUpdate["uuid"];
            },
            $existingPropertiesWithUpdates
        );

        //Format data before inserting/updating in DB
        $data = $this->formatData($propertyTypeRepository, $data);

        if (!empty($existingUuids)) {
            //Update the properties in the database if needed
            $this->updateProperties($propertyRepository, $propertyUpdateRepository, $existingUuids, $existingPropertiesWithUpdates, $data);
        }

        //Evaluate the new uuids that need to be inserted
        $newUuids = array_diff($allUuids, $existingUuids);

        if (!empty($newUuids)) {
            //Insert the properties in the database
            $this->insertProperties($propertyRepository, $propertyUpdateRepository, $newUuids, $data);
        }
    }

    protected function updateProperties(
        PropertyRepositoryInterface $propertyRepository,
        PropertyUpdateRepositoryInterface $propertyUpdateRepository,
        array $existingUuids,
        array $existingPropertiesWithUpdates,
        array $data
    ) {
        //We need to filter the properties that need to be updated in the database
        //1. We look in `property_updates` table to see if the property exists in our DB
        //2. If it does, we compare the saved `updated_at` timestamp (`property_updated_at` field in DB)
        //   against the new `updated_at` timestamp in the API response. If they are different then only that record is updated.
        //   `properties` table is updated with the content and `property_updates` table is updated with the new `updated_at` timestamp
        $dataToUpdate = [];

        foreach ($data as $dataItem) {
            if (!in_array($dataItem["uuid"], $existingUuids)) {
                continue;
            }

            $existingPropertyWithUpdateMatch = array_filter(
                $existingPropertiesWithUpdates,
                function($existingPropertyWithUpdateItem) use ($dataItem) {
                    return (
                        $dataItem["uuid"] === $existingPropertyWithUpdateItem["uuid"] &&
                        $dataItem["updated_at"] !== $existingPropertyWithUpdateItem["property_updated_at"]
                    );
                }
            );

            if (empty($existingPropertyWithUpdateMatch)) {
                continue;
            }

            $dataToUpdate[] = $dataItem;
        }

        //Perform database update
        DB::transaction(function() use ($dataToUpdate, $propertyRepository, $propertyUpdateRepository) {
            foreach($dataToUpdate as $toUpdate) {
                $propertyRepository->update($toUpdate);

                $propertyUpdateRepository->update($toUpdate["uuid"], $toUpdate["updated_at"]);
            }
        });
    }

    protected function insertProperties(
        PropertyRepositoryInterface $propertyRepository,
        PropertyUpdateRepositoryInterface $propertyUpdateRepository,
        array $newUuids,
        array $data
    ) {
        //We know which uuids are not present in our DB
        //extract the property details for those UUIDS for insertion
        $dataToInsert = array_filter(
            $data,
            function($property) use ($newUuids) {
                return in_array($property["uuid"], $newUuids);
            }
        );

        //Perform database insertion
        DB::transaction(function() use ($dataToInsert, $propertyRepository, $propertyUpdateRepository) {
            foreach ($dataToInsert as $toInsert) {
                $propertyRepository->save($toInsert);

                $propertyUpdateRepository->save($toInsert["uuid"], $toInsert["updated_at"]);
            }
        });
    }

    protected function formatData(PropertyTypeRepositoryInterface $propertyTypeRepository, array $data)
    {
        $propertyTypes = $propertyTypeRepository->findAll()->all();

        foreach ($data as $key => $dataItem) {
            //Evaluate type of property
            if ($dataItem["type"] === PropertyTypeEnum::RENT) {
                $data[$key]["for_sale"] = false;
                $data[$key]["for_rent"] = true;
            } elseif ($dataItem["type"] === PropertyTypeEnum::SALE) {
                $data[$key]["for_sale"] = true;
                $data[$key]["for_rent"] = false;
            }

            //Associate a property type from DB
            $propertyType = array_filter(
                $propertyTypes,
                function($propertyTypeItem) use ($dataItem) {
                    return $dataItem["property_type_id"] == $propertyTypeItem->property_type_id;
                }
            );

            //Found an existing type in DB
            if (!empty($propertyType)) {
                $data[$key]["property_type_id"] = reset($propertyType)->id;
                continue;
            }

            //Create new property type and associate it
            $propertyType = $propertyTypeRepository->save(
                $dataItem["property_type"]["id"],
                $dataItem["property_type"]["title"],
                $dataItem["property_type"]["description"]
            );

            $data[$key]["property_type_id"] = $propertyType->id;

            //Save the new property type data for the next iteration
            $propertyTypes[] = $propertyType;
        }

        return $data;
    }
}
