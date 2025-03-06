<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBedTypeRequest;
use App\Http\Requests\UpdateBedTypeRequest;
use App\Models\Bed;
use App\Models\BedType;
use App\Models\IpdPatientDepartment;
use App\Repositories\BedTypeRepository;

class BedTypeController extends AppBaseController
{
    /** @var BedTypeRepository */
    private $bedTypeRepository;

    public function __construct(BedTypeRepository $bedTypeRepo)
    {
        $this->bedTypeRepository = $bedTypeRepo;
    }

    public function index()
    {
        return view('bed_types.index');
    }

    public function store(CreateBedTypeRequest $request)
    {
        $input = $request->all();

        $this->bedTypeRepository->create($input);

        return $this->sendSuccess(__('messages.bed.bed_type').' '.__('messages.common.saved_successfully'));
    }

    public function show(BedType $bedType)
    {
        $beds = $bedType->beds;

        return view('bed_types.show', compact('bedType', 'beds'));
    }

    public function edit(BedType $bedType)
    {
        return $this->sendResponse($bedType, 'Bed Type retrieved successfully.');
    }

    public function update(BedType $bedType, UpdateBedTypeRequest $request)
    {
        $input = $request->all();
        $bedType = $this->bedTypeRepository->update($input, $bedType->id);

        return $this->sendSuccess(__('messages.bed.bed_type').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(BedType $bedType)
    {
        $bed = Bed::whereBedType($bedType->id)->exists();
        $ipdPatientDepartment = IpdPatientDepartment::whereBedTypeId($bedType->id)->exists();

        if ($bed || $ipdPatientDepartment) {
            return $this->sendError(__('messages.bed.bed_type').' '.__('messages.common.cant_be_deleted'));
        }

        $this->bedTypeRepository->delete($bedType->id);

        return $this->sendSuccess(__('messages.bed.bed_type').' '.__('messages.common.deleted_successfully'));
    }
}
