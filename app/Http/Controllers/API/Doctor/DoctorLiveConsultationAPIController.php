<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\AppBaseController;
use App\Models\IpdPatientDepartment;
use App\Models\LiveConsultation;
use App\Models\OpdPatientDepartment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DoctorLiveConsultationAPIController extends AppBaseController
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $live_consultations = $this->getLiveConsultancy()->get();

        $data = [];
        foreach ($live_consultations as $live_consultation) {
            $data[] = $live_consultation->prepareData();
        }

        return $this->sendResponse($data, 'Live Consultancy Retrieved Successfully');
    }

    public function liveConsultancyMeeting($id): \Illuminate\Http\JsonResponse
    {
        $live_consultation = LiveConsultation::where('id', $id)->where('doctor_id',
            getLoggedInUser()->owner_id)->first();

        if (! $live_consultation) {
            return $this->sendError(__('messages.live_consultations').' '.__('messages.common.not_found'));
        }

        if($live_consultation->status == LiveConsultation::STATUS_CANCELLED || $live_consultation->status == LiveConsultation::STATUS_FINISHED){
            return $this->sendError(__('messages.common.meeting_finished_or_cancelled'));
        }

        return $this->sendResponse($live_consultation->prepareDataForMeeting(),
            'Live Consultancy Retrieved Successfully');
    }

    public function filter(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = $request->get('status');

        if ($status == 'all') {

            $live_consultations = $this->getLiveConsultancy()->orderBy('id', 'desc')->get();

            $data = [];
            foreach ($live_consultations as $live_consultation) {
                $data[] = $live_consultation->prepareData();
            }

            return $this->sendResponse($data, 'Live Consultancy Retrieved Successfully');
        } elseif ($status == 'awaited') {

            $live_consultations = $this->getLiveConsultancy();

            $live_consultations = $live_consultations->where('status',
                LiveConsultation::STATUS_AWAITED)->where('doctor_id',
                    getLoggedInUser()->owner_id)->orderBy('id', 'desc')->get();

            $data = [];
            foreach ($live_consultations as $live_consultation) {
                $data[] = $live_consultation->prepareData();
            }
            if(!$data){
                return $this->sendError(__('messages.live_consultations').' '.__('messages.common.not_found'));
            }

            return $this->sendResponse($data, 'Live Consultancy Retrieved Successfully');
        } elseif ($status == 'cancelled') {
            $live_consultations = $this->getLiveConsultancy();

            $live_consultations = $live_consultations->where('status',
                LiveConsultation::STATUS_CANCELLED)->where('doctor_id',
                    getLoggedInUser()->owner_id)->orderBy('id', 'desc')->get();

            $data = [];
            foreach ($live_consultations as $live_consultation) {
                $data[] = $live_consultation->prepareData();
            }
            if(!$data){
                return $this->sendError(__('messages.live_consultations').' '.__('messages.common.not_found'));
            }

            return $this->sendResponse($data, 'Live Consultancy Retrieved Successfully');
        } elseif ($status == 'finished') {
            $live_consultations = $this->getLiveConsultancy();

            $live_consultations = $live_consultations->where('status',
                LiveConsultation::STATUS_FINISHED)->where('doctor_id',
                    getLoggedInUser()->owner_id)->orderBy('id', 'desc')->get();

            $data = [];
            foreach ($live_consultations as $live_consultation) {
                $data[] = $live_consultation->prepareData();
            }

            if(!$data){
                return $this->sendError(__('messages.live_consultations').' '.__('messages.common.not_found'));
            }

            return $this->sendResponse($data, 'Live Consultancy Retrieved Successfully');
        } else {
            return $this->sendError(__('messages.live_consultations').' '.__('messages.common.not_found'));
        }
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $live_consultation = LiveConsultation::where('id', $id)->where('doctor_id',
            getLoggedInUser()->owner_id)->first();

        if (! $live_consultation) {
            return $this->sendError(__('messages.live_consultations').' '.__('messages.common.not_found'));
        }

        return $this->sendResponse($live_consultation->prepareDataForDetail(),
            'Live Consultancy Retrieved Successfully');
    }

    public function getLiveConsultancy(): Builder
    {
        $ipdIds = IpdPatientDepartment::pluck('id')->toArray();
        $opdIds = OpdPatientDepartment::pluck('id')->toArray();
        $live_consultations = LiveConsultation::with([
            'patient.patientUser', 'doctor.doctorUser', 'user', 'ipdPatient', 'opdPatient',
        ])->where('doctor_id', getLoggedInUser()->owner_id);

        $live_consultations->where(function (Builder $q) use ($ipdIds, $opdIds) {
            $q->whereIn('type_number', $ipdIds)->where('type', 1)
                ->orWhereIn('type_number', $opdIds)->where('type', 0);
        });

        return $live_consultations;
    }

    public function meeting($id): JsonResponse
    {
        $live_consultation = LiveConsultation::where('id', $id)->first();

        if (! $live_consultation) {
            return $this->sendError(__('messages.live_consultations').' '.__('messages.common.not_found'));
        }

        if ($live_consultation->status == 1 || $live_consultation->status == 2) {
            return $this->sendError(__('messages.common.meeting_finished_or_cancelled'));
        }

        return $this->sendResponse($live_consultation->prepareDataForMeeting(),
            'Live Consultancy Retrieved Successfully');
    }
}
