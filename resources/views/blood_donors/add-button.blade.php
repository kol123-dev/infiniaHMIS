<div class="card-toolbar">
    <div class="d-flex align-items-center py-1">
        @if(Auth::user()->hasRole('Lab Technician'))
            <div class="dropdown">
                <a href="javascript:void(0)" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_blood_donors_modal"
                           class="dropdown-item  px-5">{{ __('messages.blood_donor.new_blood_donor') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('blood.donors.excel') }}"
                           class="dropdown-item  px-5"
                           >{{ __('messages.common.export_to_excel') }}</a>
                    </li>
                </ul>
            </div>

        @else
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_blood_donors_modal"
               class="btn btn-primary">{{ __('messages.blood_donor.new_blood_donor') }}</a>
        @endif
    </div>
</div>
