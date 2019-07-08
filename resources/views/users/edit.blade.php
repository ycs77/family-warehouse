@extends('layouts.app')

@section('title', '編輯用戶')

@section('breadcrumbs', Breadcrumbs::render('users.edit', $user))

@section('content')
    <div class="container-fluid py-2">
        <h4>編輯用戶</h4>
        <hr class="my-2">

        {!! form($form) !!}
    </div>
@endsection
