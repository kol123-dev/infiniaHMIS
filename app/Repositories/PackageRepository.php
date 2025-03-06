<?php

namespace App\Repositories;

use App\Models\Package;
use App\Models\PackageService;
use App\Models\Service;
use Arr;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Validator;

/**
 * Class PackageRepository
 *
 * @version February 25, 2020, 1:10 pm UTC
 */
class PackageRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'description',
        'discount',
        'total_amount',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Package::class;
    }

    public function getServicesList()
    {
        $service = Service::whereStatus(1)->get()->pluck('name', 'id')->sort();

        return $service;
    }

    public function getServices()
    {
        $result = Service::whereStatus(1)->orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
        $services = [];
        foreach ($result as $key => $item) {
            $services[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $services;
    }

    public function store($input)
    {
        $servicePackageItemInputArray = Arr::only($input, ['service_id', 'quantity', 'rate']);

        $package = $this->create(Arr::except($input, ['service_id', 'quantity', 'rate']));
        $totalAmount = 0;

        $packageServiceItemInput = $this->prepareInputForServicePackageItem($servicePackageItemInputArray);

        foreach ($packageServiceItemInput as $key => $data) {
            $validator = Validator::make($data, PackageService::$rules);

            if ($validator->fails()) {
                throw new UnprocessableEntityHttpException($validator->errors()->first());
            }

            $data['amount'] = $data['rate'] * $data['quantity'];
            $totalAmount += $data['amount'];

            $packageServiceItem = new PackageService($data);
            $package->packageServicesItems()->save($packageServiceItem);
        }
        $package->total_amount = $totalAmount - (($totalAmount * $input['discount']) / 100);
        $package->save();

        return $package;
    }

    public function prepareInputForServicePackageItem($input)
    {
        $items = [];
        foreach ($input as $key => $data) {
            foreach ($data as $index => $value) {
                $items[$index][$key] = $value;
                if (! (isset($items[$index]['rate']) && $key == 'rate')) {
                    continue;
                }
                $items[$index]['rate'] = removeCommaFromNumbers($items[$index]['rate']);
            }
        }

        return $items;
    }

    public function updatePackage($packageId, $input)
    {
        $servicePackageItemInputArr = Arr::only($input, ['service_id', 'quantity', 'rate', 'id']);

        $package = $this->update($input, $packageId);
        $totalAmount = 0;

        $packageServiceItemInput = $this->prepareInputForServicePackageItem($servicePackageItemInputArr);

        foreach ($packageServiceItemInput as $key => $data) {
            $validator = Validator::make($data, PackageService::$rules, [
                'service_id.integer' => __('messages.package.select_service'),
            ]);

            if ($validator->fails()) {
                throw new UnprocessableEntityHttpException($validator->errors()->first());
            }

            $data['amount'] = $data['rate'] * $data['quantity'];
            $packageServiceItemInput[$key] = $data;
            $totalAmount += $data['amount'];
        }

        $packageServiceItemRepo = app(PackageServiceItemsRepository::class);
        $packageServiceItemRepo->updatePackageServiceItem($packageServiceItemInput, $package->id);

        $package->total_amount = $totalAmount - (($totalAmount * $input['discount']) / 100);
        $package->save();

        return $package;
    }
}
