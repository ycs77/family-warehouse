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
                    <div class="card-body">
                        <h5 class="card-title">借物狀態</h5>

                        @if ($item->borrow_user)
                            @if ($item->borrow_user->id !== Auth::user()->id)
                                <div class="mb-2">
                                    現在已被
                                    @can('edit', \App\User::class)
                                        <a href="{{ route('users.show', $item->borrow_user) }}">{{ $item->borrow_user->name }}</a>
                                    @else
                                        {{ $item->borrow_user->name }}
                                    @endcan
                                    借走了
                                </div>
                            @endif

                            @if (Auth::user()->getSelfOrChildToBorrow($item))
                                <form action="{{ route('items.return', $item) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">歸還</button>
                                </form>
                            @endif
                        @else
                            <div class="mb-2">尚未借出</div>
                            @if (!Auth::user()->children->count())
                                <form action="{{ route('items.borrow', $item) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">借出</button>
                                </form>
                            @else
                                <a href="{{ route('items.borrow', $item) }}" class="btn btn-success">借出</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card d-none d-md-flex">
                    <div class="card-body">
                        <h5 class="card-title">QRCode</h5>

                        <div class="qrcode-block">
                            {!! QrCode::size(300)->generate(route('item', $item)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
    $('.btn-destroy').click(function (e) {
        e.preventDefault();
        if (confirm('確定要刪除物品 {{ $item->name }} ?')) {
            $('#form-destroy')
                .attr('action', '{{ route('items.destroy', $item) }}')
                .submit();
        }
    });
    </script>
@endpush
