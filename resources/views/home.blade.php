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

            @if ($user->role !== 'child')
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
            @endif
        </div>

        @if ($menuCategories->count())
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="h3">倉庫分類</h1>

                            <div class="row" style="margin-bottom: -1rem">
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
    </div>
@endsection
