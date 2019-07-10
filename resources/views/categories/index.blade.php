@extends('layouts.app')

@section('title', '分類管理')

@section('breadcrumbs', Breadcrumbs::render('categories.index'))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">分類管理</h1>
        <hr class="my-2">

        <div class="mb-3">
            <a href="{{ route('categories.create') }}" class="btn btn-sm btn-success">新增</a>
        </div>

        @forelse ($categories as $category)
            @include('categories._nested_category')
        @empty
            <ul class="list-group list-nested">
                <li class="list-group-item text-center text-muted">無</li>
            </ul>
        @endforelse

        <form id="form-destroy" action="#" method="POST">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

@push('script')
    <script>
    $('.btn-destroy').click(function () {
        let id = $(this).closest('li').data('id');
        let name = $(this).closest('li').data('name');
        let has_child = $(this).closest('li').data('has-child');
        let confirm_text = has_child
            ? '確定要刪除分類 ' + name + ' 及所有子分類? (不會刪除物品)'
            : '確定要刪除分類 ' + name + ' ? (不會刪除物品)';

        if (confirm(confirm_text)) {
            $('#form-destroy')
                .attr('action', '{{ route('categories.destroy', '%') }}'.replace('%', id))
                .submit();
        }
    });
    </script>
@endpush
