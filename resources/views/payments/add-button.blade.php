<div>
    <a href="{{ route('payments.excel') }}"
    class="btn btn-primary {{getCurrentLoginUserLanguageName()=='ar' ? 'ms-4' : 'me-4'}}"  >
    <i class="fa-solid fa-file-csv"></i>
    </a>

    <a href="{{ route('payments.create') }}"
       class="btn btn-primary">{{ __('messages.payment.new_payment') }}</a>

</div>
