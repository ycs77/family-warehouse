@extends('layouts.app')

@section('title', '修改用戶密碼')

@section('breadcrumbs', Breadcrumbs::render('users.password', $user))

@section('content')
    <div class="container-fluid py-2">
        <h4>修改用戶密碼</h4>
        <hr class="my-2">

        {!! form($form) !!}
    </div>
@endsection
