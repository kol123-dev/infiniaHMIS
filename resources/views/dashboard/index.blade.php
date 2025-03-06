@extends('layouts.app')
@section('title')
    {{ __('messages.dashboard.dashboard') }}
@endsection
@section('page_css')
    {{--        <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}"> --}}
    {{--        <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}"> --}}
@endsection
@section('css')
    {{--    <link rel="stylesheet" href="{{ asset('assets/css/detail-header.css') }}"> --}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <livewire:dashboard />
            {{ Form::hidden('incomeExpenseReportUrl', route('income-expense-report'), ['id' => 'dashboardIncomeExpenseReportUrl', 'class' => 'incomeExpenseReportUrl']) }}
            {{ Form::hidden('currentCurrencyName', getCurrencySymbol(), ['id' => 'dashboardCurrentCurrencyName', 'class' => 'currentCurrencyName']) }}
            {{--                        {{Form::hidden('currencies',json_encode($data['currency']),['id'=>'createBillDate','class'=>'currencies'])}} --}}
            {{ Form::hidden('income_and_expense_reports', __('messages.dashboard.income_and_expense_reports'), ['id' => 'dashboardIncome_and_expense_reports', 'class' => 'income_and_expense_reports']) }}
            {{ Form::hidden('defaultAvatarImageUrl', asset('assets/img/avatar.png'), ['id' => 'dashboardDefaultAvatarImageUrl', 'class' => 'defaultAvatarImageUrl']) }}
            {{ Form::hidden('noticeBoardUrl', url('notice-boards'), ['id' => 'dashboardNoticeBoardUrl', 'class' => 'noticeBoardUrl']) }}
            {{ Form::hidden('dashboardChart', route('dashboard.chart'), ['id' => 'dashboardChart', 'class' => 'dashboardChart']) }}
        </div>
        @include('employees.notice_boards.show_modal')
    </div>
@endsection
{{--    <script src="{{mix('assets/js/dashboard/dashboard.js')}}"></script> --}}
{{--    <script src="{{mix('assets/js/custom/input_price_format.js')}}"></script> --}}
