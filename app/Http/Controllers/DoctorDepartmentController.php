<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDoctorDepartmentRequest;
use App\Http\Requests\UpdateDoctorDepartmentRequest;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use App\Repositories\DoctorDepartmentRepository;
use Flash;

class DoctorDepartmentController extends AppBaseController
{
    /** @var DoctorDepartmentRepository */
    private $doctorDepartmentRepository;

    public function __construct(DoctorDepartmentRepository $doctorDepartmentRepo)
    {
        $this->doctorDepartmentRepository = $doctorDepartmentRepo;
    }

    public function index()
    {
        return view('doctor_departments.index');
    }

    public function store(CreateDoctorDepartmentRequest $request)
    {
        $input = $request->all();
        $this->doctorDepartmentRepository->create($input);

        return $this->sendSuccess(__('messages.doctor_department.doctor_department').' '.__('messages.common.saved_successfully'));
    }

    public function show(DoctorDepartment $doctorDepartment)
    {
        $doctors = $doctorDepartment->doctors;

        $doctorDepartment = $this->doctorDepartmentRepository->find($doctorDepartment->id);

        if (empty($doctorDepartment)) {
            Flash::error(__('messages.doctor_department.doctor_department').' '.__('messages.common.not_found'));

            return redirect(route('doctorDepartments.index'));
        }

        return view('doctor_departments.show', compact('doctors', 'doctorDepartment'));
    }

    public function edit(DoctorDepartment $doctorDepartment)
    {
        return $this->sendResponse($doctorDepartment, 'Doctor Department retrieved successfully.');
    }

    public function update(DoctorDepartment $doctorDepartment, UpdateDoctorDepartmentRequest $request)
    {
        $input = $request->all();
        $this->doctorDepartmentRepository->update($input, $doctorDepartment->id);

        return $this->sendSuccess(__('messages.doctor_department.doctor_department').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(DoctorDepartment $doctorDepartment)
    {
        $doctorDepartmentModels = [
            Doctor::class,
        ];

        $result = canDelete($doctorDepartmentModels, 'doctor_department_id', $doctorDepartment->id);

        if ($result) {
            return $this->sendError(__('messages.doctor_department.doctor_department').' '.__('messages.common.cant_be_deleted'));
        }

        $doctorDepartment->delete();

        return $this->sendSuccess(__('messages.doctor_department.doctor_department').' '.__('messages.common.deleted_successfully'));
    }
}
