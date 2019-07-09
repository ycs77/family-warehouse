@extends('layouts.app')

@section('title', $category->name)

@section('breadcrumbs', Breadcrumbs::render('categories.show', $category))

@section('content')
    <div class="container-fluid py-2">
        <h1 class="h3">{{ $category->name }}</h1>
        <hr class="my-2">

        @if ($category->children->count())
            <div class="card">
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

        {{--  --}}
    </div>
@endsection
