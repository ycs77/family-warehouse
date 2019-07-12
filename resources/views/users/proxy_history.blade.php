@extends('layouts.app')

@section('title', $user->name . ' 的代借紀錄')

@section('breadcrumbs', $is_my ?? false ? Breadcrumbs::render('history.proxy') : Breadcrumbs::render('users.history.proxy', $user))

@section('content')
    <div class="container-fluid py-2">
        <h4>{{ $user->name }} 的代借紀錄</h4>
        <hr class="my-2">

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>物品</th>
                        <th>代借對象</th>
                        <th>操作</th>
                        <th>操作時間</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrow_proxy_items as $item)
                        <tr>
                            <td><a href="{{ route('item', $item) }}">{{ $item->name }}</a></td>
                            <td>
                                <a href="{{ route('users.show', $item->pivot->user) }}">{{ $item->pivot->user->name }}</a>
                            </td>
                            <td>@include('items._borrow_badge', ['action' => $item->pivot->action])</td>
                            <td>{{ $item->pivot->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">尚未代借過物品</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $borrow_proxy_items->links() }}
        </div>
    </div>
@endsection
