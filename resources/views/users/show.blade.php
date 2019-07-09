@extends('layouts.app')

@section('title', '用戶 ' . $user->name)

@section('breadcrumbs', Breadcrumbs::render('users.show', $user))

@section('content')
    <div class="container-fluid py-2">
        <h4>用戶 {{ $user->name }}</h4>
        <hr class="my-2">

        <div class="table-responsive">
            <table class="table table-show">
                <tr>
                    <th>名字</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>帳號</th>
                    <td>{{ $user->username }}</td>
                </tr>
                <tr>
                    <th>權限</th>
                    <td>
                        @component('users/_role')
                            @slot('role', $user->role)
                        @endcomponent
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
