@extends('layouts.app')

@section('title', $category->name)

@section('breadcrumbs', Breadcrumbs::render('categories.show', $category))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">{{ $category->name }}</h1>
        <hr class="my-2">

        <p class="lead">{{ $category->description }}</p>

        @if ($category->children->count())
            <div class="card mb-4">
                <h5 class="px-3 pt-3 m-0">子分類</h5>
                <ul class="nav">
                    @foreach ($category->children as $child)
                        <li class="nav-item">
                            <a href="{{ route('category', $child) }}" class="nav-link">{{ $child->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($category->items->count())
            <h4>分類物品</h4>

            <div class="row category-row">
                @foreach ($category->items as $item)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <a href="{{ route('item', $item) }}">{{ $item->name }}</a>
                                </h5>
                                <div class="text-muted">{{ Str::limit($item->description, 50) }}</div>
                            </div>
                            @if ($item->borrow_user)
                                <div class="card-footer text-center bg-success text-white">
                                    現在已被
                                    @can('edit', \App\User::class)
                                        <a href="{{ route('users.show', $item->borrow_user) }}">{{ $item->borrow_user->name }}</a>
                                    @else
                                        {{ $item->borrow_user->name }}
                                    @endcan
                                    借走了
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
