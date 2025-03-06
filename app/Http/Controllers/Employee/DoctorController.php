<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Queries\DoctorDataTable;
use DataTables;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of((new DoctorDataTable())->get())
                ->addColumn(User::IMG_COLUMN, function (Doctor $doctor) {
                    return $doctor->doctorUser->image_url;
                })->make(true);
        }

        return view('employees.doctors.index');
    }

    public function show($id)
    {
        $doctor = Doctor::find($id);
        if (! $doctor) {
            return redirect()->back();
        }

        return view('employees.doctors.show')->with('doctor', $doctor);
    }
}
