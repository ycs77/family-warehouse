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
                <div class="card">
                    <h5 class="card-header bg-primary text-white">借物狀態</h5>
                    <div class="card-body">
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
                <div class="card">
                    <h5 class="card-header bg-primary text-white">QR code</h5>
                    <div class="card-body">
                        <div class="qrcode-block text-center">
                            {!! QrCode::size(300)->generate(Hashids::encode($item->id)) !!}
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
                        @forelse ($borrow_users as $user)
                            <tr>
                                <td><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></td>
                                <td>
                                    @if ($user->pivot->parent)
                                        <a href="{{ route('users.show', $user->pivot->parent) }}">{{ $user->pivot->parent->name }}</a>
                                    @endif
                                </td>
                                <td>@include('items._borrow_badge', ['action' => $user->pivot->action])</td>
                                <td>{{ $user->pivot->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">本物品尚未借出過</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $borrow_users->links() }}
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
