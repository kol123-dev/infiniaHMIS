<?php

namespace App\Http\Controllers;

use App\Exports\PackageExport;
use App\Http\Requests\CreatePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Models\Package;
use App\Models\PatientAdmission;
use App\Repositories\PackageRepository;
use Flash;
use DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class PackageController extends AppBaseController
{
    /** @var PackageRepository */
    private $packageRepository;

    public function __construct(PackageRepository $packageRepo)
    {
        $this->packageRepository = $packageRepo;
    }

    public function index()
    {
        return view('packages.index');
    }

    public function create()
    {
        $servicesList = $this->packageRepository->getServicesList();
        $services = $this->packageRepository->getServices();

        return view('packages.create', compact('services', 'servicesList'));
    }

    public function store(CreatePackageRequest $request)
    {
        try {
            DB::beginTransaction();
            $package = $this->packageRepository->store($request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($package, __('messages.package.package') . ' ' . __('messages.common.saved_successfully'));
    }

    public function show(Package $package)
    {
        $package = Package::with(['packageServicesItems.service'])->find($package->id);

        return view('packages.show')->with('package', $package);
    }

    public function edit(Package $package)
    {
        $package->packageServicesItems;
        $servicesList = $this->packageRepository->getServicesList();
        $services = $this->packageRepository->getServices();

        return view('packages.edit', compact('services', 'package', 'servicesList'));
    }

    public function update(Package $package, UpdatePackageRequest $request)
    {
        try {
            DB::beginTransaction();
            $package = $this->packageRepository->updatePackage($package->id, $request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($package, __('messages.package.package') . ' ' . __('messages.common.updated_successfully'));
    }

    public function destroy(Package $package)
    {
        $packageModel = [
            PatientAdmission::class,
        ];
        $result = canDelete($packageModel, 'package_id', $package->id);

        if ($result) {
            return $this->sendError(__('messages.package.package') . ' ' . __('messages.common.cant_be_deleted'));
        }

        $this->packageRepository->delete($package->id);

        return $this->sendSuccess(__('messages.package.package') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function packageExport()
    {
        $packages = Package::with('packageServicesItems')->get();
        if (!$packages) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('packages.index'));
        }
        return Excel::download(new PackageExport, 'packages-' . time() . '.xlsx');
    }
}
