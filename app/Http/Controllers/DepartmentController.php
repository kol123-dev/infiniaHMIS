<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\User;
use App\Repositories\DepartmentRepository;
use Illuminate\Http\Request;


class DepartmentController extends AppBaseController
{
    /** @var DepartmentRepository */
    private $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepo)
    {
        $this->departmentRepository = $departmentRepo;
    }

    public function index()
    {
        $activeArr = Department::ACTIVE_ARR;

        return view('departments.index')->with(['activeArr' => $activeArr]);
    }

    public function store(CreateDepartmentRequest $request)
    {
        $input = $request->all();

        $this->departmentRepository->create($input);

        return $this->sendSuccess(__('messages.department').' '.__('messages.common.saved_successfully'));
    }

    public function edit(Department $department)
    {
        return $this->sendResponse($department, 'Department retrieved successfully.');
    }

    public function update(Department $department, UpdateDepartmentRequest $request)
    {
        $this->departmentRepository->update($request->all(), $department->id);

        return $this->sendSuccess(__('messages.department').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(Department $department)
    {
        $this->departmentRepository->delete($department->id);

        return $this->sendSuccess(__('messages.department').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveDepartment(Department $department)
    {
        $department->is_active = ! $department->is_active;
        $department->save();

        return $this->sendSuccess(__('messages.department').' '.__('messages.common.updated_successfully'));
    }

    public function getUsersList(Request $request)
    {
        if (empty($request->get('id'))) {
            return $this->sendError(_('messages.users').' '.__('messages.common.not_found'));
        }

        $usersData = User::get()->where('department_id', $request->get('id'))->where('status', 1)->pluck('full_name',
            'id');

        return $this->sendResponse($usersData, 'Retrieved successfully');
    }
}
