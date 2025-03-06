<div class="row">
    <div class="col-md-4">
        <div class="form-group mb-5">
            {!! Form::label('name', __('messages.item.name') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            {!! Form::text('name', null, ['id' => 'editItemName', 'class' => 'form-control', 'required','placeholder' => __('messages.item.name')]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-5">
            {!! Form::label('item_category_id', __('messages.item.item_category') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            {{ Form::select('item_category_id', $itemCategories, null, ['class' => 'form-select', 'required', 'id' => 'editItemCategory', 'data-control' => 'select2', 'placeholder' => __('messages.common.choose') . ' ' . __('messages.item_category.item_category')]) }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-5">
            {!! Form::label('unit', __('messages.item.unit') . ':', ['class' => 'form-label']) !!}
            <span class="required"></span>
            {!! Form::text('unit', null, [
                'id' => 'editItemUnit',
                'class' => 'form-control',
                'required',
                'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")',
                'maxlength' => '4',
                'minlength' => '1',
                'placeholder' => __('messages.item.unit'),
            ]) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-5">
            {!! Form::label('description', __('messages.item.description') . ':', ['class' => 'form-label']) !!}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4,'placeholder' => __('messages.item.description')]) }}
        </div>
    </div>
    <div class="d-flex justify-content-end">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-3', 'id' => 'editItemSave']) }}
        <a href="{!! route('items.index') !!}" class="btn btn-secondary me-2">{!! __('messages.common.cancel') !!}</a>
    </div>
</div>
