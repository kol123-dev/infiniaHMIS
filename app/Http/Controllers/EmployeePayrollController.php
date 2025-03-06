<?php

namespace App\Http\Controllers;

use App\Exports\EmployeePayrollExport;
use App\Http\Requests\CreateEmployeePayrollRequest;
use App\Http\Requests\UpdateEmployeePayrollRequest;
use App\Models\EmployeePayroll;
use App\Repositories\EmployeePayrollRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class EmployeePayrollController extends AppBaseController
{
    /** @var EmployeePayrollRepository */
    private $employeePayrollRepository;

    public function __construct(EmployeePayrollRepository $employeePayrollRepo)
    {
        $this->employeePayrollRepository = $employeePayrollRepo;
    }

    public function index()
    {
        $data['statusArr'] = EmployeePayroll::STATUS_ARR;

        return view('employee_payrolls.index', $data);
    }

    public function create()
    {
        $srNo = EmployeePayroll::orderBy('id', 'desc')->value('id');
        $srNo = (! $srNo) ? 1 : $srNo + 1;
        $payrollId = strtoupper(Str::random(8));
        $types = EmployeePayroll::TYPES;
        asort($types);
        $months = EmployeePayroll::MONTHS;
        $status = EmployeePayroll::STATUS;

        return view('employee_payrolls.create', compact('srNo', 'payrollId', 'types', 'months', 'status'));
    }

    public function store(CreateEmployeePayrollRequest $request)
    {
        $input = $request->all();
        $this->employeePayrollRepository->create($input);
        $this->employeePayrollRepository->createNotification($input);
        Flash::success(__('messages.employee_payroll.employee_payroll') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('employee-payrolls.index'));
    }

    public function show(EmployeePayroll $employeePayroll)
    {
        if (checkRecordAccess($employeePayroll->owner_id)) {
            return view('errors.404');
        }

        return view('employee_payrolls.show')->with('employeePayroll', $employeePayroll);
    }

    public function edit(EmployeePayroll $employeePayroll)
    {
        $types = EmployeePayroll::TYPES;
        $status = EmployeePayroll::STATUS;
        $employeePayroll->month = array_search($employeePayroll->month, EmployeePayroll::MONTHS);

        return view('employee_payrolls.edit', compact('employeePayroll', 'types', 'status'));
    }

    public function update(EmployeePayroll $employeePayroll, UpdateEmployeePayrollRequest $request)
    {
        $input = $request->all();
        $this->employeePayrollRepository->update($input, $employeePayroll->id);
        Flash::success(__('messages.employee_payroll.employee_payroll') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('employee-payrolls.index'));
    }

    public function destroy(EmployeePayroll $employeePayroll)
    {
        $employeePayroll->delete();

        return $this->sendSuccess(__('messages.employee_payroll.employee_payroll') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function getEmployeesList(Request $request)
    {
        if (empty($request->get('id'))) {
            return $this->sendError(__('messages.employee_payroll.employees_list_not_found'));
        }

        if ($request->id == 2) {
            $employeesData = EmployeePayroll::CLASS_TYPES[$request->id]::with('doctorUser')
                ->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id');
        } else {
            $employeesData = EmployeePayroll::CLASS_TYPES[$request->id]::with('user')
                ->get()->where('user.status', '=', 1)->pluck('user.full_name', 'id');
        }

        return $this->sendResponse($employeesData, 'Retrieved successfully');
    }

    public function employeePayrollExport()
    {
        $employeePayroll = EmployeePayroll::with('owner.user')->get();

        if (!$employeePayroll) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('employee-payrolls.index'));
        }
        return Excel::download(new EmployeePayrollExport, 'employee-payrolls-' . time() . '.xlsx');
    }

    public function showModal(EmployeePayroll $employeePayroll)
    {
        if ($employeePayroll->type_string == 'Doctor') {
            $employeePayroll->load(['owner.doctorUser']);
        } else {
            $employeePayroll->load(['owner.user']);
        }

        $currency = $employeePayroll->currency_symbol ? strtoupper($employeePayroll->currency_symbol) : strtoupper(getCurrentCurrency());
        $employeePayroll = [
            'sr_no' => $employeePayroll->sr_no,
            'payroll_id' => $employeePayroll->payroll_id,
            'type_string' => $employeePayroll->type_string,
            'full_name' => $employeePayroll->owner->user->full_name,
            'month' => $employeePayroll->month,
            'year' => $employeePayroll->year,
            'basic_salary' => checkValidCurrency($employeePayroll->currency_symbol ?? getCurrentCurrency()) ? moneyFormat(
                $employeePayroll->basic_salary,
                $currency
            ) : number_format($employeePayroll->basic_salary) . '' . getCurrencySymbol(),
            'allowance' => checkValidCurrency($employeePayroll->currency_symbol ?? getCurrentCurrency()) ? moneyFormat(
                $employeePayroll->allowance,
                $currency
            ) : number_format($employeePayroll->allowance) . '' . getCurrencySymbol(),
            'deductions' => checkValidCurrency($employeePayroll->currency_symbol ?? getCurrentCurrency()) ? moneyFormat(
                $employeePayroll->deductions,
                $currency
            ) : number_format($employeePayroll->deductions) . '' . getCurrencySymbol(),
            'net_salary' => checkValidCurrency($employeePayroll->currency_symbol ?? getCurrentCurrency()) ? moneyFormat(
                $employeePayroll->net_salary,
                $currency
            ) : number_format($employeePayroll->net_salary) . '' . getCurrencySymbol(),
            'status' => $employeePayroll->status,
            'created_at' => $employeePayroll->created_at,
            'updated_on' => $employeePayroll->updated_at,
        ];

        return $this->sendResponse($employeePayroll, 'Employee Payroll Retrieved Successfully.');
    }
}
