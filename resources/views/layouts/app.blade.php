@extends('layouts.html')

@section('layout-content')
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name') }}
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
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

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-sidebar">
            <ul class="nav navbar-nav nav-root dashboard-sidebar-sticky">
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="event.preventDefault()">
                        <div class="nav-link-icon">
                            <i class="fas fa-fw fa-user"></i>
                        </div>
                        <div class="nav-link-name">項目</div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="dashboard-sidebar-overlay"></div>

        <div class="dashboard-content-wrapper">
            @include('includes.alerts', ['full' => true])

            <div class="dashboard-breadcrumb">
                <div>
                    <button class="dashboard-sidebar-toggler" type="button" aria-label="Toggle navigation">
                        <span class="dashboard-sidebar-toggler-icon"></span>
                    </button>
                </div>

                <?php
                /*@ breadcrumb([
                    'items' => $menu->breadcrumbList([
                        ['name' => '我的', 'url' => route('my')],
                    ]),
                ])
                @ endbreadcrumb*/
                ?>
            </div>

            <div class="dashboard-content">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
