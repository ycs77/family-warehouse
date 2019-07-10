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
                @if ($user->role !== 'child')
                    <tr>
                        <th class="py-4">代管小孩</th>
                        <td>
                            <div class="list-group">
                                @forelse ($user->children as $child)
                                    <a href="{{ route('users.show', $child) }}" class="list-group-item list-group-item-action">
                                        {{ $child->name }}
                                    </a>
                                @empty
                                    <div class="list-group-item text-center text-muted">沒有代管小孩...</div>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
@endsection
