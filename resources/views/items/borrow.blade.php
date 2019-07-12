@extends('layouts.app')

@section('title', '借出物品')

@section('breadcrumbs', Breadcrumbs::render('items.borrow', $item))

@section('content')
    <div class="container-fluid py-2">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center">借出物品「{{ $item->name }}」</h4>
                <hr>

                @if (!$item->borrow_user)
                    <p class="lead text-center">請選擇操作身分</p>

                    <div class="list-group list-group-thin m-auto">
                        <button class="list-group-item list-group-item-action text-primary btn-borrow-user" data-user-id="{{ Auth::user()->id }}">
                            <i class="fas fa-user fa-fw"></i>
                            {{ Auth::user()->name }} (本人)
                        </button>
                        @foreach (Auth::user()->children as $child)
                            <button class="list-group-item list-group-item-action text-primary btn-borrow-user" data-user-id="{{ $child->id }}">
                                <i class="fas fa-user fa-fw"></i>
                                {{ $child->name }}
                            </button>
                        @endforeach
                    </div>

                    <form id="form-borrow" action="#" method="post">
                        @csrf
                    </form>
                @else
                    <p class="h5 text-center text-danger">無法借出 {{ $item->name }}，因為已經被 {{ $item->borrow_user->name }} 借走了！</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@if (!$item->borrow_user)
    @push('script')
        <script>
        $('.btn-borrow-user').click(function () {
            const user_id = $(this).data('user-id');
            $('#form-borrow')
                .attr('action', '{{ route('items.borrow', [$item, '%']) }}'.replace('%', user_id))
                .submit();
        });
        </script>
    @endpush
@endif
