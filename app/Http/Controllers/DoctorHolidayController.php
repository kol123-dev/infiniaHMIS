<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHolidayRequest;
use App\Models\Doctor;
use App\Models\DoctorHoliday;
use App\Models\User;
use App\Repositories\HolidayRepository;
use Flash;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class DoctorHolidayController extends AppBaseController
{
    /** @var HolidayRepository */
    private $holidayRepository;

    public function __construct(HolidayRepository $holidayRepo)
    {
        $this->holidayRepository = $holidayRepo;
    }

    public function index()
    {
        return view('doctor_holiday.index');
    }

    public function create()
    {
        $doctor = Doctor::with('user')->get()->where('user.status', User::INACTIVE)->pluck('user.full_name','id');

        return view('doctor_holiday.create', compact('doctor'));
    }

    public function store(CreateHolidayRequest $request)
    {
        $input = $request->all();

        $opdDates = Appointment::whereDate('opd_date','=',$input['date'])->whereIsCompleted(1)->whereDoctorId($input['doctor_id'])->exists();

        if($opdDates){
            Flash::error(__('messages.holiday.appointment_exists'));

            return redirect(route('holidays.create'));
        }

        $holiday = $this->holidayRepository->store($input);

        if ($holiday) {
            Flash::success(__('messages.holiday.doctor_holiday_create'));

            return redirect(route('holidays.index'));
        } else {
            Flash::error(__('messages.holiday.holiday_already_is_exist'));

            return redirect(route('holidays.create'));
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request,$id)
    {
        //
    }

    public function destroy($id)
    {
        $checkRecord = DoctorHoliday::destroy($id);

        return $this->sendSuccess(__('messages.flash.city_delete'));
    }

    public function holiday()
    {
        return view('holiday.index');
    }

    public function doctorCreate()
    {
        $doctor = Doctor::whereUserId(getLoggedInUserId())->first('id');
        $doctorId = $doctor['id'];

        return view('holiday.create', compact('doctorId'));
    }

    public function doctorStore(CreateHolidayRequest $request)
    {
        $input = $request->all();
        $holiday = $this->holidayRepository->store($input);

        if ($holiday) {
            Flash::success(__('messages.holiday.doctor_holiday_create'));

            return redirect(route('doctors.holiday'));
        } else {
            Flash::error(__('messages.holiday.holiday_already_is_exist'));

            return redirect(route('doctors.holiday-create'));
        }
    }

    public function doctorDestroy($id)
    {
        $doctorHoliday = DoctorHoliday::whereId($id)->first();

        if ($doctorHoliday->doctor_id !== getLoggedInUser()->doctor->id) {
            return $this->sendError(__('messages.holiday.you_are_not_allow_to_record'));
        }

        $doctorHoliday->destroy($id);

        return $this->sendSuccess(__('messages.flash.city_delete'));
    }
}
