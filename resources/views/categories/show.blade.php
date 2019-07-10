@extends('layouts.app')

@section('title', $category->name)

@section('breadcrumbs', Breadcrumbs::render('categories.show', $category))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">{{ $category->name }}</h1>
        <hr class="my-2">

        <p class="lead">{{ $category->description }}</p>

        @can('edit', \App\Category::class)
            <div class="mb-3">
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-success">修改</a>
                <button class="btn btn-sm btn-danger btn-destroy">刪除</button>
            </div>
        @endcan

        @if ($category->children->count())
            <div class="card mb-4">
                <h5 class="px-3 pt-3 m-0">子分類</h5>
                <ul class="nav">
                    @foreach ($category->children as $child)
                        <li class="nav-item">
                            <a href="{{ route('category', $child) }}" class="nav-link">{{ $child->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($category->items->count())
            <h4>分類物品</h4>

            <div class="row category-row">
                @foreach ($category->items as $item)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <a href="{{ route('item', $item) }}">{{ $item->name }}</a>
                                </h5>
                                <div class="text-muted">{{ Str::limit($item->description, 50) }}</div>
                            </div>
                            @if ($item->borrow_user)
                                <div class="card-footer text-center bg-success text-white">
                                    現在已被
                                    @can('edit', \App\User::class)
                                        <a href="{{ route('users.show', $item->borrow_user) }}">{{ $item->borrow_user->name }}</a>
                                    @else
                                        {{ $item->borrow_user->name }}
                                    @endcan
                                    借走了
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <form id="form-destroy" action="#" method="POST">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('script')
    <script>
    $('.btn-destroy').click(function () {
        if (confirm('{{ $category->children->count() ? "確定要刪除分類 {$category->name} 及所有子分類? (不會刪除物品)" : "確定要刪除分類 {$category->name}? (不會刪除物品)" }}')) {
            $('#form-destroy')
                .attr('action', '{{ route('categories.destroy', $category) }}')
                .submit();
        }
    });
    </script>
@endpush
