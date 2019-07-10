@extends('layouts.app')

@section('title', '編輯物品')

@section('breadcrumbs', Breadcrumbs::render('items.edit', $item))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">編輯物品</h1>
        <hr class="my-2">

        {!! form_start($form) !!}

        {!! form_until($form, 'description') !!}

        @include('categories._categories_field', [
            'name' => 'category_id',
            'label' => '分類',
            'current' => null,
            'checked_id' => $item->category ? $item->category->id : null,
            'root_label' => '(無分類)',
        ])

        @if ($item->borrow_user)
            <div class="form-group row">
                <label for="name" class="col-lg-2 col-form-label text-lg-right">歸還</label>
                <div class="col-lg-10">
                    <button type="submit" form="return-item" class="btn btn-danger btn-sm">強制歸還此物品</button>
                </div>
            </div>
        @endif

        {!! form_end($form) !!}
    </div>

    @if ($item->borrow_user)
        <form id="return-item" action="{{ route('items.return', $item) }}" method="POST">
            @csrf
        </form>
    @endif
@endsection
