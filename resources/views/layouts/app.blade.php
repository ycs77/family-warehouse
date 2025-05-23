@extends('layouts.html')

@section('layout-content')
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name') }}
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                @can('view', App\Item::class)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('scanner.index') }}">
                            <i class="fas fa-camera fa-fw"></i> 掃描 QR code
                        </a>
                    </li>
                @endcan
            </ul>

            <ul class="navbar-nav ml-auto align-items-md-center">
                <li class="nav-item mr-md-2">
                    <form id="search" action="{{ items_search_route() }}" method="GET" class="form-inline input-group input-group-sm my-2 my-md-0" >
                        <input class="form-control" type="search" name="q" placeholder="搜尋物品..." value="{{ request()->query('q') }}" aria-label="Search" required>
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit">搜尋</button>
                        </div>
                    </form>
                </li>

                {{-- Authentication Links --}}
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" class="d-none" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        {{-- Dashboard Sidebar --}}
        <div class="dashboard-sidebar">
            <ul class="nav navbar-nav nav-root dashboard-sidebar-content">
                <li class="nav-item @active('home')">
                    <a class="nav-link" href="{{ route('home') }}">
                        <div class="nav-link-icon">
                            <i class="fas fa-fw fa-home"></i>
                        </div>
                        <div class="nav-link-name">首頁</div>
                    </a>
                </li>

                @can('view', App\Item::class)
                    <li class="nav-item @active('scanner.*')">
                        <a class="nav-link" href="{{ route('scanner.index') }}">
                            <div class="nav-link-icon">
                                <i class="fas fa-camera fa-fw"></i>
                            </div>
                            <div class="nav-link-name">掃描 QR code</div>
                        </a>
                    </li>
                @endcan

                @can('view', App\Category::class)
                    <li class="nav-item @active('items.(index|create)')">
                        <a class="nav-link" href="{{ route('items.index') }}">
                            <div class="nav-link-icon">
                                <i class="fas fa-boxes fa-fw"></i>
                            </div>
                            <div class="nav-link-name">物品列表</div>
                        </a>
                    </li>

                    @foreach ($menuCategories as $menuCategory)
                    <li class="nav-item @category_active($menuCategory)">
                        <a class="nav-link" href="{{ route('categories.show', $menuCategory) }}">
                            <div class="nav-link-icon">
                                <i class="{{ $menuCategory->icon ? $menuCategory->icon : 'fas fa-cube' }} fa-fw"></i>
                            </div>
                            <div class="nav-link-name">{{ $menuCategory->name }}</div>
                        </a>
                    </li>
                    @endforeach
                @endcan

                @can('view', App\Item::class)
                    <li class="nav-item @active('history.borrow')">
                        <a class="nav-link" href="{{ route('history.borrow') }}">
                            <div class="nav-link-icon">
                                <i class="fas fa-fw fa-list-ul"></i>
                            </div>
                            <div class="nav-link-name">借物紀錄</div>
                        </a>
                    </li>
                    @if (Auth::user()->role !== 'child')
                        <li class="nav-item @active('history.proxy')">
                            <a class="nav-link" href="{{ route('history.proxy') }}">
                                <div class="nav-link-icon">
                                    <i class="fas fa-fw fa-list-ul"></i>
                                </div>
                                <div class="nav-link-name">代借紀錄</div>
                            </a>
                        </li>
                    @endif
                @endcan

                @can('edit', App\User::class)
                    <li class="nav-item @active('users.*')">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <div class="nav-link-icon">
                                <i class="fas fa-fw fa-user"></i>
                            </div>
                            <div class="nav-link-name">用戶管理</div>
                        </a>
                    </li>
                @endcan

                @can('edit', App\Category::class)
                    <li class="nav-item @active('categories.(index|create)')">
                        <a class="nav-link" href="{{ route('categories.index') }}">
                            <div class="nav-link-icon">
                                <i class="fas fa-fw fa-cubes"></i>
                            </div>
                            <div class="nav-link-name">分類管理</div>
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
        <div class="dashboard-sidebar-overlay"></div>

        {{-- Dashboard Content --}}
        <div class="dashboard-content-wrapper">
            @include('includes.alerts', ['full' => true])

            <div class="dashboard-breadcrumb">
                <div>
                    <button class="dashboard-sidebar-toggler" type="button" aria-label="Toggle navigation">
                        <span class="dashboard-sidebar-toggler-icon"></span>
                    </button>
                </div>

                @yield('breadcrumbs')
            </div>

            <div class="dashboard-content">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
