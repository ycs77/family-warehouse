@extends('layouts.app')

@section('title', '用戶管理')

@section('breadcrumbs', Breadcrumbs::render('users.index'))

@section('content')
    <div class="container-fluid py-2">
        <h4>用戶管理</h4>
        <hr class="my-2">

        <div class="mb-2">
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-success">新增</a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名字</th>
                        <th>帳號</th>
                        <th>權限</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                            <td>{{ $user->id }}</td>
                            <td><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></td>
                            <td>{{ $user->username }}</td>
                            <td>
                                @component('users/_permission')
                                    @slot('permission', $user->permission)
                                @endcomponent
                            </td>
                            <td>
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">查看</a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-success">修改</a>
                                <a href="{{ route('users.password', $user) }}" class="btn btn-sm btn-warning">修改密碼</a>
                                @if (!$user->isCantDeprivation())
                                    <button class="btn btn-sm btn-danger btn-user-destroy">刪除</button>
                                @else
                                    <button class="btn btn-sm btn-danger" disabled>刪除</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form id="form-user-destroy" action="#" method="POST">
                @csrf
                @method('DELETE')
            </form>

            {{ $users->links() }}
        </div>
    </div>
@endsection

@push('script')
    <script>
    $('.btn-user-destroy').click(function (e) {
        e.preventDefault();
        id = $(this).closest('tr').data('id');
        name = $(this).closest('tr').data('name');
        if (confirm('確定要刪除用戶 ' + name + ' ?')) {
            $('#form-user-destroy')
                .attr('action', '{{ route('users.destroy', '%') }}'.replace('%', id))
                .submit();
        }
    });
    </script>
@endpush
