@extends('layouts.app')

@section('title', '新增分類')

@section('breadcrumbs', Breadcrumbs::render('categories.create'))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">新增分類</h1>
        <hr class="my-2">

        {!! form_start($form) !!}

        {!! form_until($form, 'description') !!}

        @include('categories._categories_field', [
            'label' => '上層分類',
            'current' => null,
            'parent_id' => null,
        ])

        {!! form_end($form) !!}
    </div>
@endsection
