@extends('layouts.app')

@section('title', '用戶 ' . $user->name)

@section('breadcrumbs', Breadcrumbs::render('users.show', $user))

@section('content')
    <div class="container-fluid py-2">
        <h4>用戶 {{ $user->name }}</h4>
        <hr class="my-2">

        <div class="mb-3">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-success">修改</a>
            <a href="{{ route('users.password', $user) }}" class="btn btn-sm btn-warning">修改密碼</a>
            @if (!$user->isCantDeprivation())
                <button type="button" class="btn btn-sm btn-danger btn-destroy">刪除</button>
            @endif
        </div>

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
                <tr>
                    <th class="py-4">借出物品</th>
                    <td>
                        <div class="list-group list-group-thin">
                            @forelse ($user->borrows as $borrowItem)
                                <a href="{{ route('item', $borrowItem) }}" class="list-group-item list-group-item-action text-primary">
                                    <i class="fas fa-box fa-fw"></i>
                                    {{ $borrowItem->name }}
                                </a>
                            @empty
                                <div class="list-group-item text-center text-muted">沒有借出物品</div>
                            @endforelse
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>借物紀錄</th>
                    <td><a href="{{ route('users.history.borrow', $user) }}">查看</a></td>
                </tr>
                @if ($user->role !== 'child')
                    <tr>
                        <th class="py-4">代管小孩</th>
                        <td>
                            <div class="list-group list-group-thin">
                                @forelse ($user->children as $child)
                                    <a href="{{ route('users.show', $child) }}" class="list-group-item list-group-item-action text-primary">
                                        <i class="fas fa-user fa-fw"></i>
                                        {{ $child->name }}
                                    </a>
                                @empty
                                    <div class="list-group-item text-center text-muted">沒有代管小孩</div>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>代借紀錄</th>
                        <td><a href="{{ route('users.history.proxy', $user) }}">查看</a></td>
                    </tr>
                @endif
            </table>

            <form id="form-destroy" action="#" method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
    $('.btn-destroy').click(function (e) {
        if (confirm('確定要刪除用戶 {{ $user->name }} ?')) {
            $('#form-destroy')
                .attr('action', '{{ route('users.destroy', $user) }}')
                .submit();
        }
    });
    </script>
@endpush
