<div>
<a href="{{ route('incomes.excel') }}"
class="btn btn-primary {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4'}}"  >
<i class="fas fa-file-excel"></i>
</a>

<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_incomes_modal"
    class="btn btn-primary">{{ __('messages.incomes.new_income') }}
</a>
</div>
