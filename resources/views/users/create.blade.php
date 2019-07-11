@extends('layouts.app')

@section('title', '新增用戶')

@section('breadcrumbs', Breadcrumbs::render('users.create'))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">新增用戶</h1>
        <hr class="my-2">

        {!! form_start($form) !!}

        {!! form_until($form, 'role') !!}

        @include('users._children_field')

        {!! form_row($form->submit) !!}

        {!! form_end($form, false) !!}
    </div>
@endsection
