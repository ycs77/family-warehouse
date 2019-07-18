@extends('layouts.app')

@section('title', '新增子分類')

@section('breadcrumbs', Breadcrumbs::render('categories.sub.create', $category))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">新增 {{ $category->name }} 的子分類</h1>
        <hr class="my-2">

        {!! form($form) !!}
    </div>
@endsection
