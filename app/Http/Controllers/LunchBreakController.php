<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLunchBreakRequest;
use App\Models\Doctor;
use App\Models\LunchBreak;
use App\Models\User;
use App\Repositories\LunchBreakRepository;
use Flash;
use App\Models\Appointment;

class LunchBreakController extends AppBaseController
{
    /** @var LunchBreakRepository */
    private $lunchBreakRepository;

    public function __construct(LunchBreakRepository $lunchBreakRepository)
    {
        $this->lunchBreakRepository = $lunchBreakRepository;
    }

    public function index()
    {
        return view('lunch_breaks.index');
    }

    public function create()
    {
        $doctor = Doctor::with('user')->get()->where('user.status', User::INACTIVE)->pluck('user.full_name','id');

        return view('lunch_breaks.create', compact('doctor'));
    }

    public function store(CreateLunchBreakRequest $request)
    {
        $input = $request->all();

        if(isset($input['date']) && !empty($input['date'])){
            $opdDates = Appointment::whereRaw('DATE(opd_date) = ?', $input['date'])->exists();

            if($opdDates){
                Flash::error(__('messages.lunch_break.appointment_exists'));

                return redirect(route('breaks.create'));
            }
        }

        $lunchBreak = $this->lunchBreakRepository->store($input);

        if ($lunchBreak) {
            Flash::success(__('messages.lunch_break.break_create'));

            return redirect(route('breaks.index'));
        } else {
            Flash::error(__('messages.lunch_break.break_already_is_exist'));

            return redirect(route('breaks.create'));
        }
    }

    public function destroy($id)
    {
        $checkRecord = LunchBreak::destroy($id);

        return $this->sendSuccess(__('messages.lunch_break.lunch_break').' '.__('messages.common.deleted_successfully'));
    }
}
