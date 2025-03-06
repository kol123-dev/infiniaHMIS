<div>
      <a href="{{ route('expenses.excel') }}"
      class="btn btn-primary {{getCurrentLoginUserLanguageName() == 'ar' ? 'ms-4' : 'me-4'}}"  >
      <i class="fas fa-file-excel"></i>

    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_expenses_modal"
       class="btn btn-primary">{{ __('messages.expense.new_expense') }}</a>

</div>
