<?php

namespace App\Http\Controllers\Employee;

use App\Exports\UserPayrollExport;
use App\Http\Controllers\Controller;
use App\Models\EmployeePayroll;
use App\Queries\EmployeePayrollDataTable;
use DataTables;
use Exception;
use Flash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of((new EmployeePayrollDataTable())->get())->make(true);
        }

        return view('employees.payrolls.index');
    }

    public function userPayrollExport()
    {
        $employeePayrolls = EmployeePayroll::with('owner.user')->where(
            'owner_id',
            getLoggedInUser()->owner_id
        )->where('owner_type', getLoggedInUser()->owner_type)->get();


        if ($employeePayrolls->count() == 0) {
            Flash::error(__('messages.common.no_data_available'));
            return redirect(route('payroll'));
        }
        return Excel::download(new UserPayrollExport, getLoggedInUser()->full_name . '-payroll-' . time() . '.xlsx');
    }
}
