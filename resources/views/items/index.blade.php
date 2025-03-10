@extends('layouts.app')

@inject('itemPresenter', '\App\Presenters\ItemPresenter')

@section('title', '物品列表')

@section('breadcrumbs', Breadcrumbs::render('items.index'))

@section('content')
    <div class="container-fluid py-2">
        <div class="header-actions">
            <h1 class="h3">物品列表</h1>
            <a href="{{ route('items.create') }}" class="btn btn-sm btn-success">新增</a>
        </div>
        <hr class="my-2">

        <div class="mb-2">
            <div class="form-inline d-inline-flex ml-3">
                篩選：
                <div class="input-group input-group-sm">
                    <select class="form-control" onchange="location.href = this.value">
                        <option value="{{ current_route_query(['borrow' => '']) }}">已借物 & 未借物</option>
                        <option value="{{ current_route_query(['borrow' => 'true']) }}" {{ $borrow === 'true' ? 'selected' : '' }}>已借物</option>
                        <option value="{{ current_route_query(['borrow' => 'false']) }}" {{ $borrow === 'false' ? 'selected' : '' }}>未借物</option>
                    </select>
                </div>
            </div>
        </div>

        @if ($filterResult = $itemPresenter->statusText($borrow, $search))
            <div class="my-3 text-muted">
                <i class="fas fa-hourglass-end"></i>
                {{ $filterResult }}
                <a href="{{ route('items.index') }}">清除篩選</a>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>物品</th>
                        <th>簡介</th>
                        <th>分類</th>
                        <th>借物</th>

                        @can('edit', \App\Item::class)
                            <th>操作</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                            <td>{{ $item->id }}</td>
                            <td><a href="{{ route('items.show', $item) }}">{{ $item->name }}</a></td>
                            <td>{{ $item->description }}</td>
                            <td>
                                @if ($item->category)
                                    <a href="{{ route('categories.show', $item->category) }}">{{ $item->category->name }}</a>
                                @else
                                    <span class="text-muted">無</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->borrow_user)
                                    <a href="{{ route('users.show', $item->borrow_user) }}">{{ $item->borrow_user->name }}</a>
                                @else
                                    <span class="text-muted">無</span>
                                @endif
                            </td>

                            @can('edit', \App\Item::class)
                                <td>
                                    <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-success">修改</a>
                                    <button class="btn btn-sm btn-danger btn-destroy">刪除</button>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="@can('edit', \App\Item::class) 6 @else 5 @endcan" class="text-empty">無</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <form id="form-destroy" action="#" method="POST">
                @csrf
                @method('DELETE')
            </form>

            {{ $items->appends(compact('borrow', 's'))->links() }}
        </div>
    </div>
@endsection

@push('script')
    <script>
    $('.btn-destroy').click(function () {
        let id = $(this).closest('tr').data('id');
        let name = $(this).closest('tr').data('name');
        if (confirm('確定要刪除物品 ' + name + ' ?')) {
            $('#form-destroy')
                .attr('action', '{{ route('items.destroy', '%') }}'.replace('%', id))
                .submit();
        }
    });
    </script>
@endpush
