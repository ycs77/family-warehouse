@extends('layouts.app')

@section('title', '編輯分類')

@section('breadcrumbs', Breadcrumbs::render('categories.edit', $category))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">編輯分類</h1>
        <hr class="my-2">

        {!! form_start($form) !!}

        {!! form_until($form, 'description') !!}

        @include('categories._categories_field', [
            'label' => '上層分類',
            'current' => $category,
            'parent_id' => $category->parent_id,
        ])

        {!! form_end($form) !!}
    </div>
@endsection
