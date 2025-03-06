<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\LiveConsultation;
use App\Repositories\LiveConsultationRepository;
use App\Repositories\PatientCaseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiveConsultationAPIController extends AppBaseController
{
    /** @var LiveConsultationRepository */
    private $liveConsultationRepository;

    /** @var PatientCaseRepository */
    private $patientCaseRepository;

    /**
     * LiveConsultationController constructor.
     */
    public function __construct(
        LiveConsultationRepository $liveConsultationRepository,
        PatientCaseRepository $patientCaseRepository
    ) {
        $this->liveConsultationRepository = $liveConsultationRepository;
        $this->patientCaseRepository = $patientCaseRepository;
    }

    public function index(): JsonResponse
    {
        $liveConsultations = LiveConsultation::whereHas('patient.patientUser')->whereHas('doctor.doctorUser')->whereHas('user')->with([
            'patient.patientUser', 'doctor.doctorUser', 'user',
        ])->filter()->where('patient_id', getLoggedInUser()->owner_id)->orderBy('id', 'desc')->get();

        $data = [];
        foreach ($liveConsultations as $liveConsultation) {
            $data[] = $liveConsultation->prepareLiveConsultation();
        }

        return $this->sendResponse($data, 'Live Consultation Retrieved successfully.');
    }

    public function show($id): JsonResponse
    {
        $liveConsultation = LiveConsultation::with([
            'user', 'patient.patientUser', 'doctor.doctorUser', 'opdPatient', 'ipdPatient',
        ])->where('id', $id)->where('patient_id', getLoggedInUser()->owner_id)->first();

        if (! $liveConsultation) {
            return $this->sendError(__('messages.live_consultations').' '.__('messages.common.not_found'));
        }

        return $this->sendResponse($liveConsultation->prepareLiveConsultationDetail(),
            'Live Consultation Retrieved successfully.');
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

    public function filter(Request $request): JsonResponse
    {
        $status = $request->get('status');

        $live_consultancy = $this->liveConsultationRepository->filter($status);

        $data = [];
        foreach ($live_consultancy as $liveConsultation) {
            $data[] = $liveConsultation->prepareLiveConsultation();
        }

        if(!$data){
            return $this->sendError(__('messages.live_consultations').' '.__('messages.common.not_found'));
        }

        return $this->sendResponse($data, 'Live Consultancy Retrieved Successfully');
    }
}
