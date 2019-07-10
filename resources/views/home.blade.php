@extends('layouts.app')

@section('title', '首頁')

@section('breadcrumbs', Breadcrumbs::render('home'))

@section('content')
    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-md mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h1 class="h3">{{ $user->name }}</h1>
                        <div class="text-muted">{{ $user->username }}</div>
                        @component('users/_role')
                            @slot('role', $user->role)
                        @endcomponent
                    </div>
                </div>
            </div>

            @can('view', App\Item::class)
                <div class="col-md mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">借出物品</h5>

                            <div class="list-group">
                                @forelse ($user->borrows as $borrowItem)
                                    <a href="{{ route('item', $borrowItem) }}" class="list-group-item list-group-item-action">
                                        {{ $borrowItem->name }}
                                    </a>
                                @empty
                                    <div class="list-group-item text-center text-muted">沒有借出物品...</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>

        @if ($user->role !== 'child')
            <div class="row">
                <div class="col-md mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">代管小孩</h5>

                            <div class="list-group">
                                @forelse ($user->children as $child)
                                    <a href="{{ route('users.show', $child) }}" class="list-group-item list-group-item-action">
                                        {{ $child->name }}
                                    </a>
                                @empty
                                    <div class="list-group-item text-center text-muted">沒有代管小孩...</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">小孩借出物品</h5>

                            @forelse ($user->children()->has('borrows')->get() as $child)
                                <div class="card mt-3">
                                    <h5 class="card-header bg-white">{{ $child->name }}</h5>
                                    <div class="list-group list-group-flush">
                                        @foreach ($child->borrows as $borrow)
                                            <a href="{{ route('item', $borrow) }}" class="list-group-item list-group-item-action">
                                                {{ $borrow->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="py-3 text-center text-muted">現在小孩都沒有借出物品...</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @can('view', App\Category::class)
            @if ($menuCategories->count())
                <div class="row">
                    <div class="col mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="h3">倉庫分類</h1>

                                <div class="row category-row">
                                    @foreach ($menuCategories as $menuCategory)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                                            <a href="{{ route('category', $menuCategory) }}" class="card">
                                                <div class="card-body text-center">
                                                    <i class="{{ $menuCategory->icon ? $menuCategory->icon : 'fas fa-cube' }} fa-2x"></i>
                                                    <div class="card-title h5 mt-3 mb-0">{{ $menuCategory->name }}</div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endcan
    </div>
@endsection
