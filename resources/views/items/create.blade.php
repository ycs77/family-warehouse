@extends('layouts.app')

@section('title', '新增物品')

@section('breadcrumbs', Breadcrumbs::render('items.create'))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">新增物品</h1>
        <hr class="my-2">

        {!! form_start($form) !!}

        {!! form_until($form, 'description') !!}

        @include('categories._categories_field', [
            'name' => 'category_id',
            'label' => '分類',
            'current' => null,
            'checked_id' => null,
            'root_label' => '(無分類)',
        ])

        {!! form_end($form) !!}
    </div>
@endsection
