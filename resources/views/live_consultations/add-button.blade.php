<div class="dropdown">
    @if(!Auth::user()->hasRole('Patient'))
        <a href="#" class="btn btn-primary {{getCurrentLoginUserLanguageName()== 'ar' ? 'ms-5' : 'me-5'}}" id="dropdownMenuButton" data-bs-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">{{ __('messages.common.actions') }}
            <i class="fa fa-chevron-down"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li>
                <a href="javascript:void(0)" class="dropdown-item" data-bs-toggle="modal"
                   data-bs-target="#add_consulatation_modal">{{ __('messages.live_consultation.new_live_consultation') }}</a>
            </li>
            <li>
                <a href="javascript:void(0)"
                   class="dropdown-item add-credential">{{ __('messages.live_consultation.add_credential') }}</a>
            </li>
        </ul>

        @if(isZoomTokenExpire())
            <a type="button" class="btn btn-success mr-5 ml-5" href="{{route('zoom.connect')}}">
                {{ __('messages.subscription_plans.connect_with_zoom') }}
            </a>
        @endif
    @endif
</div>
