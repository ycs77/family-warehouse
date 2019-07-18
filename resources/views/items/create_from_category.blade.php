@extends('layouts.app')

@section('title', '新增物品')

@section('breadcrumbs', Breadcrumbs::render('categories.item.create', $category))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">新增 {{ $category->name }} 的物品</h1>
        <hr class="my-2">

        {!! form($form) !!}
    </div>
@endsection
