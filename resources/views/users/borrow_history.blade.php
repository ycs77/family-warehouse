@extends('layouts.app')

@section('title', $user->name . ' 的借物紀錄')

@section('breadcrumbs', $is_my ?? false ? Breadcrumbs::render('history.borrow') : Breadcrumbs::render('users.history.borrow', $user))

@section('content')
    <div class="container-fluid py-2">
        <h4>{{ $user->name }} 的借物紀錄</h4>
        <hr class="my-2">

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>物品</th>
                        <th>代借者</th>
                        <th>操作</th>
                        <th>操作時間</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $history)
                        <tr>
                            <td><a href="{{ route('items.show', $history->item) }}">{{ $history->item->name }}</a></td>
                            <td>
                                @if ($history->parent)
                                    <a href="{{ route('users.show', $history->parent) }}">{{ $history->parent->name }}</a>
                                @else
                                    <span class="text-muted">無</span>
                                @endif
                            </td>
                            <td>@include('items._borrow_badge', ['action' => $history->action])</td>
                            <td>{{ $history->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">尚未借過物品</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $histories->links() }}
        </div>
    </div>
@endsection
