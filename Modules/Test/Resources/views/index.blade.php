@extends('test::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('test.name') !!}</p>
@endsection
