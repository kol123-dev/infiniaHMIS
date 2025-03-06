<div class="row">
    <div class="col-md-4 col-sm-6 co-12">
        <div class="image mb-7">
            <img src="{{ $data['app_logo'] }}" alt="user" class="img-fluid max-width-180">
        </div>
        <h3>{{ !empty($prescription['prescription']->doctor->doctorUser->full_name) ? $prescription['prescription']->doctor->doctorUser->full_name :
 ''}}</h3>
        <h4 class="fs-5 text-gray-600 fw-light mb-0">
            {{ !empty($prescription['prescription']->doctor->specialist) ? $prescription['prescription']->doctor->specialist : $prescription['prescription']->doctor->specialist }}
        </h4>
    </div>
    <div class="col-md-4 col-sm-6 co-12 mt-sm-0 mt-5">
        <div class="d-flex flex-row">
            <label for="name" class="pb-2 fs-5 text-gray-600 {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-1' : 'me-1'}}">{{ __('messages.bill.patient_name') }}:</label>
            <span class="fs-5 text-gray-800">
                {{ !empty($prescription['prescription']->patient->patientUser->full_name) ? $prescription['prescription']->patient->patientUser->full_name : '' }}
            </span>
        </div>
        <div class="d-flex flex-row">
            <label for="name" class="pb-2 fs-5 text-gray-600 {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-1' : 'me-1'}}">{{ __('messages.expense.date') }}:</label>
            <span class="fs-5 text-gray-800">
                {{ !empty(\Carbon\Carbon::parse($prescription['prescription']->created_at)->isoFormat('DD/MM/Y')) ? \Carbon\Carbon::parse($prescription['prescription']->created_at)->isoFormat('DD/MM/Y') : ''}}
            </span>
        </div>
        <div class="d-flex flex-row">
            <label for="name" class="pb-2 fs-5 text-gray-600 {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-1' : 'me-1'}}">{{ __('messages.blood_donor.age') }}:</label>
            <span class="fs-5 text-gray-800">
                @if($prescription['prescription']->patient->patientUser->dob)
                    {{ \Carbon\Carbon::parse($prescription['prescription']->patient->patientUser->dob)->diff(\Carbon\Carbon::now())->y }}
                    {{ __('messages.prescription.year') }}
                @else
                    {{ __('messages.common.n/a') }}
                @endif
            </span>
        </div>
    </div>
    <div class="col-md-4 col-12 mt-md-0 mt-5">
        <p class="text-gray-600 mb-3">
            @if(!empty($data['hospital_address']))
                {{ $data['hospital_address'] }}
            @else
                {{ __('messages.common.n/a') }}
            @endif
        </p>
    </div>
    <div class="col-md-4 co-12 mt-md-0 mt-5">
        @if(empty($prescription['prescription']->doctor->address->address1) && empty($prescription['prescription']->doctor->address->address2) && empty($prescription['prescription']->doctor->address->city))
            {{ __('messages.common.n/a') }}
        @else
            {{ !empty($prescription['prescription']->doctor->address->address1) ? $prescription['prescription']->doctor->address->address1 : '' }}
            {{ !empty($prescription['prescription']->doctor->address->address2) ? !empty($prescription['prescription']->doctor->address->address1) ? ',' : '' : '' }}
            {{ empty($prescription['prescription']->doctor->address->address1) || !empty($prescription['prescription']->doctor->address->address2)  ? !empty($prescription['prescription']->doctor->address->address2) ? $prescription['prescription']->doctor->address->address2  : '' : ''  }}
            {{ !empty($prescription['prescription']->doctor->address->city) ? ',' : '' }}
            @if(!empty($prescription['prescription']->doctor->address->city))
                <br>
            @endif
            {{ !empty($prescription['prescription']->doctor->address->city) ? $prescription['prescription']->doctor->address->city : '' }}
            {{ !empty($prescription['prescription']->doctor->address->zip) ? ',' : '' }}
            @if($prescription['prescription']->doctor->address->zip)
                <br>
            @endif
            {{ !empty($prescription['prescription']->doctor->address->zip) ? $prescription['prescription']->doctor->address->zip : '' }}
            <p class="text-gray-600 mb-3">
                {{ !empty($prescription['prescription']->doctor->user->phone) ? $prescription['prescription']->doctor->user->phone : '' }}
            </p>
            <p class="text-gray-600 mb-3">
                {{ !empty($prescription['prescription']->doctor->user->email) ? $prescription['prescription']->doctor->user->email : '' }}
            </p>
        @endif
    </div>
    <div class="col-12 px-0">
        <hr class="line my-lg-10 mb-6 mt-4">
    </div>
    <div class="col-md-4 col-sm-6 co-12">
        <h6>{{ __('messages.prescription.problem') }}:</h6>
        @if($prescription['prescription']->problem_description != null)
            <p class="text-gray-600 mb-2 fs-4">{{ $prescription['prescription']->problem_description }}</p>
        @else
            {{ __('messages.common.n/a') }}
        @endif
    </div>
    <div class="col-md-4 col-sm-6 co-12 mt-sm-0 mt-5">
        <h6>{{ __('messages.prescription.test') }}:</h6>
        @if($prescription['prescription']->test != null)
            <p class="text-gray-600 mb-2 fs-4">{{ $prescription['prescription']->test }}</p>
        @else
            {{ __('messages.common.n/a') }}
        @endif
    </div>
    <div class="col-md-4 col-sm-6 co-12 mt-md-0 mt-5">
        <h6>{{ __('messages.prescription.advice') }}:</h6>
        @if($prescription['prescription']->advice != null)
            <p class="text-gray-600  mb-2 fs-4">{{ $prescription['prescription']->advice }}</p>
        @else
            {{ __('messages.common.n/a') }}
        @endif
    </div>
    <div class="col-12 px-0 mb-6 mt-4">

    </div>
    @if (!empty($prescription['prescription']->food_allergies))
    <div class="col-md-4 col-sm-6 co-12">
        <h5 class="lh-lg text-gray-600 me-1 fs-5">
            {{ __('messages.prescription.food_allergies') }}:
            <span class="text-dark"> {{ $prescription['prescription']->food_allergies }}</span>
        </h5>
    </div>
@endif
@if (!empty($prescription['prescription']->tendency_bleed))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.tendency_bleed') }}:
            <span class="text-dark"> {{ $prescription['prescription']->tendency_bleed }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->heart_disease))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.heart_disease') }}:
            <span class="text-dark"> {{ $prescription['prescription']->heart_disease }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->high_blood_pressure))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.high_blood_pressure') }}:
            <span class="text-dark"> {{ $prescription['prescription']->high_blood_pressure }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->diabetic))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.diabetic') }}:
            <span class="text-dark"> {{ $prescription['prescription']->diabetic }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->surgery))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.surgery') }}:
            <span class="text-dark"> {{ $prescription['prescription']->surgery }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->accident))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.accident') }}:
            <span class="text-dark">{{ $prescription['prescription']->accident }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->others))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.others') }}:
            <span class="text-dark">{{ $prescription['prescription']->others }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->medical_history))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.medical_history') }}:
            <span class="text-dark">{{ $prescription['prescription']->medical_history }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->current_medication))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.current_medication') }}:
            <span class="text-dark">{{ $prescription['prescription']->current_medication }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->female_pregnancy))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.female_pregnancy') }}:
            <span class="text-dark"> {{ $prescription['prescription']->female_pregnancy }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->breast_feeding))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.breast_feeding') }}:
            <span class="text-dark">{{ $prescription['prescription']->breast_feeding }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->plus_rate))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.plus_rate') }}:
            <span class="text-dark">{{ $prescription['prescription']->plus_rate }}</span>
        </h6>
    </div>
@endif
@if (!empty($prescription['prescription']->temperature))
    <div class="col-md-4 col-sm-6 co-12">
        <h6 class="lh-lg text-gray-600 me-1 fs-5">{{ __('messages.prescription.temperature') }}:
            <span class="text-dark">{{ $prescription['prescription']->temperature }}</span>
        </h6>
    </div>
@endif
    <div class="col-12 mt-6">
        <h6>{{ __('messages.prescription.rx') }}:</h6>
        <div class="table-responsive">
            <table class="table box-shadow-none">
                <thead>
                <tr>
                    <th scope="col">{{ __('messages.prescription.medicine_name') }}</th>
                    <th scope="col">{{ __('messages.ipd_patient_prescription.dosage') }}</th>
                    <th scope="col">{{ __('messages.prescription.duration') }}</th>
                    <th scope="col">{{ __('messages.medicine_bills.dose_interval') }}</th>
                </tr>
                </thead>
                <tbody>
                @if(empty($medicines))
                    <tr>
                        <td class="text-center" colspan="3">
                            {{ __('messages.prescription.no_data_available') }}
                        </td>
                    </tr>
                @else
                    @foreach($prescription['prescription']->getMedicine as $medicine)
                        @foreach($medicine->medicines as $medi)
{{--                            @foreach($medi as $md)--}}
                                <tr>
                                    <td class="py-4 border-bottom-0">{{ $medi->name }}</td>
                                    <td class="py-4 border-bottom-0">
                                        {{ $medicine->dosage }}
                                        @if($medicine->time == 0)
                                            ({{ __('messages.prescription.after_meal') }})
                                        @else
                                            ({{  __('messages.prescription.before_meal')}})
                                        @endif
                                    </td>
                                    <td class="py-4 border-bottom-0">{{ $medicine->day }} Day</td>
                                    @if ($medicine->dose_interval != 0)
                                    <td class="py-4 border-bottom-0">{{ App\Models\Prescription::DOSE_INTERVAL[$medicine->dose_interval] }}</td>
                                    @endif
                                </tr>
{{--                            @endforeach--}}
                            @break
                        @endforeach
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap mt-5">
            <h4 class="mb-0 me-3 mt-3">
                @if($prescription['prescription']->next_visit_qty != null)
                    {{ __('messages.prescription.next_visit') }} : {{ $prescription['prescription']->next_visit_qty }}
                    @if($prescription['prescription']->next_visit_time == 0)
                        {{ __('messages.prescription.days') }}
                    @elseif($prescription['prescription']->next_visit_time == 1)
                        {{ __('messages.prescription.month') }}
                    @else
                        {{ __('messages.prescription.year') }}
                    @endif
                @endif
            </h4>
            <div class="mt-3">
                <br>
                <h4>{{ 'Dr. '.$prescription['prescription']->doctor->doctorUser->full_name }}</h4>
                <h6 class="text-gray-600 fw-light mb-0">{{ $prescription['prescription']->doctor->specialist }}</h6>
            </div>
        </div>
    </div>
</div>
