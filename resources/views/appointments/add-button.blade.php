<div class="d-flex align-items-center flex-wrap justify-content-end">
    <div class="{{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-3' : 'me-3'}}">
        <input class="form-control custom-width" id="time_range" /><b class="caret"></b>
    </div>

    @if (Auth::user()->hasRole('Admin'))
        <div class="d-flex align-items-center py-1">
            <a  href="{{ route('appointments.create') }}"
                class="btn btn-primary">{{ __('messages.appointment.new_appointment') }}</a>
        </div>
    @endif
    @if (Auth::user()->hasRole('Doctor'))
        <div class="d-flex align-items-center py-1">
            <a  href="{{ route('appointments.excel') }}"
                class="btn btn-primary">{{ __('messages.common.export_to_excel') }}</a>
        </div>
    @endif
    @if (Auth::user()->hasRole('Patient|Receptionist'))
        <div class="dropdown pt-1">
            <a href="#" class="btn btn-primary" id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
                <i class="fa-solid fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li>
                    <a  href="{{ route('appointments.create') }}" class="dropdown-item  px-5">
                        {{ __('messages.appointment.new_appointment') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('appointments.excel') }}" class="dropdown-item  px-5" >
                        {{ __('messages.common.export_to_excel') }}
                    </a>
                </li>
            </ul>
        </div>
    @endif
</div>
