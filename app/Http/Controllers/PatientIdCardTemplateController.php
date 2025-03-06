<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PatientIdCardTemplateRepository;
use App\Models\PatientIdCardTemplate;
use Laracasts\Flash\Flash;
use App\Http\Requests\CreatePatientIdCardTemplateRequest;
use App\Http\Requests\UpdatePatientIdCardTemplateRequest;
use App\Models\Patient;

class PatientIdCardTemplateController extends AppBaseController
{
    /**
     * @var PatientIdCardTemplateRepository
     */
    private $PatientIdCardTemplateRepository;

    public function __construct(PatientIdCardTemplateRepository $PatientIdCardTemplateRepository)
    {
        $this->PatientIdCardTemplateRepository = $PatientIdCardTemplateRepository;
    }

    public function index()
    {
        return view('patient_id_card_template.index');
    }

    public function create()
    {
        return view('patient_id_card_template.create');
    }

    public function store(CreatePatientIdCardTemplateRequest $request)
    {
        $input = $request->all();
        $this->PatientIdCardTemplateRepository->create($input);

        Flash::success(__('messages.patient_id_card.new_patient_id_card').' '.__('messages.common.saved_successfully'));

        return redirect(route('smart-patient-cards.index'));
    }

    public function edit($id)
    {
        $patientIdCardTemplateData = PatientIdCardTemplate::find($id);

        return view('patient_id_card_template.edit', compact('patientIdCardTemplateData'));
    }

    public function update(UpdatePatientIdCardTemplateRequest $request, $id)
    {
        $this->PatientIdCardTemplateRepository->update($id, $request->all());

        Flash::success(__('messages.patient_id_card.new_patient_id_card').' '.__('messages.common.updated_successfully'));

        return redirect(route('smart-patient-cards.index'));
    }

    public function destroy($id)
    {
        Patient::where('template_id',$id)->update(['template_id' => null]);
        PatientIdCardTemplate::find($id)->delete();

        return $this->sendSuccess(__('messages.patient_id_card.patient_id_card').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus(Request $request, $id)
    {
        $patientIdCardTemplateData = PatientIdCardTemplate::find($id);

        if(isset($request->color)){
            $patientIdCardTemplateData->update(['color' => $request->color]);
        }else{
            $patientIdCardTemplateData->update([$request->name => $request->status]);
        }


        return $this->sendSuccess(__('messages.user.status').' '.__('messages.common.updated_successfully'));
    }
}
