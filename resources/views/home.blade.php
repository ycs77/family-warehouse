@extends('layouts.app')

@section('title', '首頁')

@section('breadcrumbs', Breadcrumbs::render('home'))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">Home</h1>
        <hr>
        <p class="lead">Text...</p>
    </div>
@endsection
