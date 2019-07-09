@extends('layouts.app')

@section('title', '編輯用戶')

@section('breadcrumbs', Breadcrumbs::render('users.edit', $user))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">編輯用戶</h1>
        <hr class="my-2">

        {!! form($form) !!}
    </div>
@endsection
