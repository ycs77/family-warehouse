@extends('layouts.app')

@section('title', '首頁')

@section('breadcrumbs', Breadcrumbs::render('home'))

@section('content')
    <div class="container-fluid py-2">
        <h4>Home</h4>
        <hr>
        <p class="lead">Text...</p>
    </div>
@endsection
