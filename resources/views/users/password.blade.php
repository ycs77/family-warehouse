@extends('layouts.app')

@section('title', '修改密碼')

@section('breadcrumbs', Breadcrumbs::render('users.password', $user))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">修改密碼</h1>
        <hr class="my-2">

        {!! form($form) !!}
    </div>
@endsection
