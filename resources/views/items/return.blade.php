@extends('layouts.app')

@section('title', '歸還物品')

@section('breadcrumbs', Breadcrumbs::render('items.return', $item))

@section('content')
    <div class="container-fluid py-2">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center">歸還物品「{{ $item->name }}」</h4>
                <hr>

                <form action="{{ route('items.return', $item) }}" method="POST">
                    @csrf
                    <div class="text-center">
                        <button class="btn btn-primary">歸還</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
