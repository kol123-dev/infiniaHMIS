@if(Auth::user()->hasRole('Admin'))
    <a  href="{{ route('payment.report.excel') }}" class="btn btn-primary">
        {{__('messages.common.export_to_excel')}}
    </a>
@endif
