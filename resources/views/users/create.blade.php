@extends('layouts.app')

@section('title', '新增用戶')

@section('breadcrumbs', Breadcrumbs::render('users.create'))

@section('content')
    <div class="container-fluid py-2">
        <h4>新增用戶</h4>
        <hr class="my-2">

        {!! form($form) !!}
    </div>
@endsection
