<?php

namespace App\Repositories;

use App\Models\Package;
use App\Models\PackageService;

/**
 * Class PackageServiceItemsRepository
 *
 * @version February 13, 2020, 9:51 am UTC
 */
class PackageServiceItemsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'service_id',
        'package_id',
        'quantity',
        'rate',
        'amount',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return PackageService::class;
    }

    public function updatePackageServiceItem($packageServiceItemInput, $packageId)
    {
        $package = Package::find($packageId);
        $packageServiceItemIds = [];

        foreach ($packageServiceItemInput as $key => $data) {
            if (isset($data['id']) && ! empty($data['id'])) {
                $packageServiceItemIds[] = $data['id'];
                $this->update($data, $data['id']);
            } else {
                $packageServiceItem = new PackageService($data);
                $packageServiceItem = $package->packageServicesItems()->save($packageServiceItem);
                $packageServiceItemIds[] = $packageServiceItem->id;
            }
        }

        if (! (isset($packageServiceItemIds) && count($packageServiceItemIds))) {
            return;
        }
        PackageService::whereNotIn('id', $packageServiceItemIds)->wherePackageId($package->id)->delete();
    }
}
