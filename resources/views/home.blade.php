@extends('layouts.app')

@section('title', '首頁')

@section('breadcrumbs', Breadcrumbs::render('home'))

@section('content')
    <div class="container-fluid pb-3">
        <div class="row">
            <div class="col-md-12 col-lg mb-4">
                <div class="card card-center h-100">
                    <div class="card-body">
                        <div>
                            <h1 class="h3">
                                <i class="fas fa-user fa-fw text-primary"></i>{{ $user->name }}
                            </h1>
                            <div>
                                <span class="text-muted">{{ $user->username }}</span>
                                @component('users/_role')
                                    @slot('role', $user->role)
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @can('view', App\Item::class)
                <div class="col-md-6 col-lg mb-4">
                    <a href="{{ route('scanner.index') }}" class="card card-center h-100">
                        <div class="card-body">
                            <div>
                                <i class="fas fa-camera fa-3x"></i>
                                <div class="card-title h5 mt-3 mb-0">掃描 QR code</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-lg mb-4">
                    <div class="card">
                        <h5 class="card-header bg-primary text-white">借出物品</h5>
                        <div class="list-group list-group-flush">
                            @forelse ($user->borrows as $borrowItem)
                                <a href="{{ route('item', $borrowItem) }}" class="list-group-item list-group-item-action text-primary">
                                    <i class="fas fa-box fa-fw"></i>
                                    {{ $borrowItem->name }}
                                </a>
                            @empty
                                <div class="list-group-item text-center text-muted">沒有借出物品</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endcan
        </div>

        @if ($user->role !== 'child')
            <div class="card mb-3">
                <h5 class="card-header bg-primary text-white">小孩借出物品</h5>
                <div class="card-body">
                    <div class="row">
                        @forelse ($user->children as $child)
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-3">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class="mb-0">
                                            <i class="fas fa-user text-primary"></i>
                                            {{ $child->name }}
                                        </h5>
                                        <a href="{{ route('users.show', $child) }}">用戶詳情</a>
                                    </div>
                                    <div class="list-group list-group-flush">
                                        @forelse ($child->borrows as $borrow)
                                            <a href="{{ route('item', $borrow) }}" class="list-group-item list-group-item-action text-primary">
                                                <i class="fas fa-box fa-fw"></i>
                                                {{ $borrow->name }}
                                            </a>
                                        @empty
                                            <div class="list-group-item text-center text-muted">沒有借出物品</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col py-3 text-center text-muted">現在小孩都沒有借出物品</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        @can('view', App\Category::class)
            @if ($menuCategories->count())
                <div class="row">
                    <div class="col mb-4">
                        <div class="card">
                            <h5 class="card-header bg-primary text-white">倉庫分類</h5>
                            <div class="card-body">
                                <div class="row category-row">
                                    @foreach ($menuCategories as $menuCategory)
                                        <div class="col-6 col-sm-4 col-lg-3 col-xl-2 mb-3">
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
