@if (Auth::user()->hasRole('Patient'))
    <a  href="{{ route('prescription.excel') }}"
       class="btn btn-primary">{{ __('messages.common.export_to_excel') }}</a>
@endif
