@extends('layouts.app')

@section('title', '掃描 QR code')

@section('breadcrumbs', Breadcrumbs::render('scanner'))

@section('content')
    <div class="container-fluid py-2">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-danger">
                    <i class="fas fa-exclamation-triangle fa-3x"></i>
                    <h4 class="mt-3">掃描失敗</h4>
                </div>
                <hr>

                <a href="{{ route('scanner.index') }}" class="btn btn-primary">再試一次</a>
            </div>
        </div>
    </div>
@endsection
