<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="form-group mb-5">
            {!! Form::label('department_id', __('messages.issued_item.department_id') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            {{ Form::select('department_id', $data['userTypes'], null, ['id' => 'issueUserType', 'class' => 'form-select', 'required', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.issued_item.department_id'), 'data-control' => 'select2']) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="form-group mb-5">
            {!! Form::label('user_id', __('messages.issued_item.user_id') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            {{ Form::select('user_id', [null], null, ['id' => 'issueTo', 'class' => 'form-select', 'required', 'disabled', 'data-control' => 'select2', 'placeholder' => __('messages.message.select_user')]) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="form-group mb-5">
            {!! Form::label('issued_by', __('messages.issued_item.issued_by') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            {!! Form::text('issued_by', null, ['id'=>'issuedBy', 'class' => 'form-control', 'required','placeholder'=>__('messages.issued_item.issued_by')]) !!}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="form-group mb-5">
            {!! Form::label('issued_date', __('messages.issued_item.issued_date') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            {!! Form::text('issued_date', null, ['placeholder' => __('messages.issued_item.issued_date'),'id'=>'issueDate', 'class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'), 'required', 'autocomplete' => 'off','placeholder'=>__('messages.issued_item.issued_date')]) !!}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="form-group mb-5">
            {!! Form::label('return_date', __('messages.issued_item.return_date') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            {!! Form::text('return_date', null, ['id'=>'issueReturnDate', 'class' => (getLoggedInUser()->thememode ? 'bg-light form-control' : 'bg-white form-control'), 'autocomplete' => 'off','placeholder'=>__('messages.issued_item.return_date')]) !!}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="form-group mb-5">
            {!! Form::label('item_category_id', __('messages.issued_item.item_category') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            {{ Form::select('item_category_id', $data['itemCategories'], null, ['id' => 'issueItemCategory', 'class' => 'form-select', 'required', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.item_category.item_category'), 'data-control' => 'select2']) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="form-group mb-5">
            {!! Form::label('item_id', __('messages.issued_item.item') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            {{ Form::select('item_id', [null], null, ['id' => 'issueItems', 'class' => 'form-select', 'required', 'disabled', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.item.item')]) }}
        </div>
    </div>
    <div class="col-lg-4 col-md-5 col-sm-12">
        <div class="form-group mb-5">
            {!! Form::hidden('available_quantity', null, ['id' => 'itemAvailableQuantity']) !!}
            {!! Form::label('quantity', __('messages.issued_item.quantity') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            (<span
                class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{ __('messages.item.available_quantity') . ':' }}
                <span id="showAvailableQuantity">0</span></span>)
            {!! Form::number('quantity', null, [
                'id' => 'itemQuantity',
                'class' => 'form-control',
                'required',
                'min' => 1,
                'disabled',
                'placeholder' => __('messages.issued_item.quantity'),
            ]) !!}
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="form-group mb-5">
            {{ Form::label('description', __('messages.item_stock.description').(':'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4,'placeholder'=>__('messages.issued_item.description')]) }}
        </div>
    </div>
    <div class="d-flex justify-content-end">
        {!! Form::submit(__('messages.common.save'), ['class' => (getCurrentLoginUserLanguageName()=='ar' ? 'btn btn-primary ms-3' : 'btn btn-primary me-3'), 'id' => 'issuedItemSave']) !!}
        <a href="{!! route('issued.item.index') !!}" class="btn btn-secondary">{!! __('messages.common.cancel') !!}</a>
    </div>
</div>
