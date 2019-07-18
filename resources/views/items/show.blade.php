@extends('layouts.app')

@section('title', $item->name)

@section('breadcrumbs', Breadcrumbs::render('items.show', $item))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">{{ $item->name }}</h1>
        <hr class="my-2">

        <p class="lead">{{ $item->description }}</p>

        @can('edit', \App\Item::class)
            <div class="mb-3">
                <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-success">修改</a>
                <button class="btn btn-sm btn-danger btn-destroy">刪除</button>
            </div>
        @endcan

        <div class="row">
            <div class="col-md mb-3">
                <div class="card h-100">
                    <h5 class="card-header bg-primary text-white">借物狀態</h5>
                    <div class="card-body text-center">
                        @if ($item->borrow_user)
                            <div class="mb-2">
                                現在已被
                                @can('edit', \App\User::class)
                                    <a href="{{ route('users.show', $item->borrow_user) }}">{{ $item->borrow_user->name }}</a>
                                @else
                                    {{ $item->borrow_user->name }}
                                @endcan
                                借走了
                            </div>

                            @if (Auth::user()->getSelfOrChildToBorrow($item))
                                <form action="{{ route('items.return', $item) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">歸還</button>
                                </form>
                            @endif
                        @else
                            <div class="mb-2">尚未借出</div>
                            <a href="{{ route('items.borrow.page', $item) }}" class="btn btn-success">借出</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md mb-3">
                <div class="card h-100">
                    <h5 class="card-header bg-primary text-white">QR code</h5>
                    <div class="card-body">
                        <div class="text-center">
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#qrcodeModal">
                                顯示 QR code
                            </button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="qrcodeModal" tabindex="-1" role="dialog" aria-labelledby="qrcodeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="qrcodeModalLabel">
                                            <i class="fas fa-qrcode fa-fw"></i>
                                            {{ $item->name }} QR code
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body qrcode-block text-center">
                                        <img src="{{ route('items.qrcode', $item) }}" alt="{{ $item->name }} QR code">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header bg-primary text-white">借物紀錄</h5>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>用戶</th>
                            <th>代借者</th>
                            <th>操作</th>
                            <th>操作時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr>
                                <td><a href="{{ route('users.show', $history->user) }}">{{ $history->user->name }}</a></td>
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
                                <td colspan="4" class="text-empty">本物品尚未借出過</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $histories->links() }}
        </div>
    </div>
@endsection

@push('script')
    <script>
    $('.btn-destroy').click(function () {
        if (confirm('確定要刪除物品 {{ $item->name }} ?')) {
            $('#form-destroy')
                .attr('action', '{{ route('items.destroy', $item) }}')
                .submit();
        }
    });
    </script>
@endpush
