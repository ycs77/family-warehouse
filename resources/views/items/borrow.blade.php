@extends('layouts.app')

@section('title', '借出物品')

@section('breadcrumbs', Breadcrumbs::render('items.borrow', $item))

@section('content')
    <div class="container-fluid py-2">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center">借出物品</h4>
                <hr>

                <h5 class="text-center">請選擇操作身分</h5>

                <div class="list-group list-group-users">
                    <button class="list-group-item list-group-item-action btn-borrow-user" data-user-id="{{ Auth::user()->id }}">
                        {{ Auth::user()->name }} (本人)
                    </button>
                    @foreach (Auth::user()->children as $child)
                        <button class="list-group-item list-group-item-action btn-borrow-user" data-user-id="{{ $child->id }}">
                            {{ $child->name }}
                        </button>
                    @endforeach
                </div>

                <form id="form-borrow" action="#" method="post">
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
    .list-group-users {
        max-width: 300px;
        margin: auto;
    }
    </style>
@endpush

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
