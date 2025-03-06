<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Patient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PatientIdCardTemplate;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Requests\CreatePatientIdCardRequest;
use App\Repositories\GeneratePatientIdCardRepository;

class GeneratePatientIdCardController extends AppBaseController
{
    /** @var GeneratePatientIdCardRepository */
    private $GeneratePatientIdCardRepositorie;

    public function __construct(GeneratePatientIdCardRepository $GeneratePatientIdCardRepositorie)
    {
        $this->GeneratePatientIdCardRepositorie = $GeneratePatientIdCardRepositorie;
    }

    public function index()
    {
        $templates = $this->GeneratePatientIdCardRepositorie->getTemplates();
        $patients = $this->GeneratePatientIdCardRepositorie->getPatients();

        return view('generate_patient_id_card.index', compact('templates','patients'));
    }

    public function store(CreatePatientIdCardRequest $request)
    {
        $input = $request->all();
        $this->GeneratePatientIdCardRepositorie->store($input);

        return $this->sendSuccess(__('messages.patient_id_card.patient_id_card').' '.__('messages.common.saved_successfully'));
    }

    public function show($uniqueId)
    {
        $patients = Patient::with(['patientUser','address','idCardTemplate'])->where('patient_unique_id',$uniqueId)->first();
        if(empty($patients->patient_unique_id)){
            $patients->update(['patient_unique_id' => strtoupper(Patient::generateUniquePatientId())]);
        }
        return $this->sendResponse($patients, 'Data retrieved successfully.');
    }

    public function destroy($id)
    {
        Patient::find($id)->update(['template_id' => null]);

        return $this->sendSuccess(__('messages.patient_id_card.patient_id_card').' '.__('messages.common.deleted_successfully'));
    }

    public function downloadIdCard($id)
    {
        $patientIdCardData =  Patient::with(['patientUser','address','idCardTemplate'])->find($id);
        $patientIdCardTemplateData = PatientIdCardTemplate::find($patientIdCardData->idCardTemplate->id);
        if(empty($patientIdCardData->patient_unique_id)){
            $patientIdCardData->update(['patient_unique_id' => strtoupper(Patient::generateUniquePatientId())]);
        }
        $url = route('qrcode.patient.show', $patientIdCardData->patient_unique_id);
        $qrCode = QrCode::size(90)->generate($url);
        $imgUrl = $patientIdCardData->patientUser->image_url;
        $arrUrl = explode('/', trim($imgUrl))[2];

        if($arrUrl == "ui-avatars.com"){
            $avatarUrl = "https://ui-avatars.com/api/?name=".$patientIdCardData->patientUser->full_name."&size=100&rounded=true&color=fff&background=fc6369";
            $avatarData = file_get_contents($avatarUrl);
            $data['profile'] = base64_encode($avatarData);
        }else{
            $avatarUrl = $imgUrl;
            $avatarData = file_get_contents($avatarUrl);
            $data['profile'] = base64_encode($avatarData);
        }

        $pdf = PDF::loadView('generate_patient_id_card.patient_id_card_pdf', compact('patientIdCardData','patientIdCardTemplateData','qrCode','data'));
        return $pdf->download($patientIdCardData->patientUser->full_name.'-'.$patientIdCardData->id.'.pdf');
    }

    public function generateQrCode($uniqueId)
    {
        $url = route('qrcode.patient.show', $uniqueId);
        $qrCode = QrCode::size(90)->generate($url);

        return $qrCode;
    }
}
