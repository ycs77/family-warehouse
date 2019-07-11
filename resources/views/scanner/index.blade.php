@extends('layouts.app')

@section('title', '掃描 QR code')

@section('breadcrumbs', Breadcrumbs::render('scanner'))

@section('content')
    <div id="scanner-preview" class="text-white">
        <div class="scanner-loading show"><div class="spinner-border"></div></div>
        <div class="scanner-error"></div>
    </div>

    <form id="form-decode" action="{{ route('scanner.decode') }}" method="post">
        @csrf
        <input type="hidden" name="code">
    </form>
@endsection

@push('script')
    <script src="{{ asset('js/instascan.min.js') }}"></script>
    <script>
    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            let scanner = new Instascan.Scanner({ video: document.getElementById('scanner-preview') });
            scanner.addListener('scan', function (content) {
                $('#form-decode input[type=hidden][name=code]').val(content);
                $('#form-decode').submit();
            });
            scanner.start(cameras[0]);
        } else {
            $('.scanner-error').addClass('show').text('偵測不到攝影機');
            $('.scanner-loading').removeClass('show');
        }
    }).catch(function (e) {
        $('.scanner-error').addClass('show').text('錯誤');
        $('.scanner-loading').removeClass('show');
    });
    </script>
@endpush

@push('style')
    <style>
    .dashboard-content-wrapper {
        display: flex;
        flex-direction: column;
    }
    .dashboard-content {
        flex: 1;
    }
    #scanner-preview {
        height: 100%;
    }
    div#scanner-preview {
        background-color: #000;
    }
    div#scanner-preview .scanner-loading,
    div#scanner-preview .scanner-error {
        height: 100%;
        display: none;
        justify-content: center;
        align-items: center;
    }
    div#scanner-preview .scanner-loading.show,
    div#scanner-preview .scanner-error.show {
        display: flex;
    }
    </style>
@endpush
