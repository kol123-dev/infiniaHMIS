@if($row->document_url !== '')
    <a target="_blank" href="{{ url('expense-download').'/'.$row->id }}" class="text-decoration-none">{{__('messages.expense.download')}}</a>
@else
    <samp>{{ __('messages.common.n/a') }}</samp>
@endif
