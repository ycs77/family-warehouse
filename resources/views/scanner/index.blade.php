@extends('layouts.app')

@section('title', '掃描 QR code')

@section('breadcrumbs', Breadcrumbs::render('scanner'))

@section('content')
    <div class="scanner-preview" class="text-white">
        <div class="scanner-loading show"><div class="spinner-border"></div></div>
        <div class="scanner-error"></div>
        <video id="scanner"></video>
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
            $('.scanner-loading').removeClass('show');
            $('#scanner').addClass('show');
            let frontCamera = cameras.find(function (camera) {
                return camera.name.indexOf('front') !== -1;
            });
            let backCamera = cameras.find(function (camera) {
                return camera.name.indexOf('back') !== -1;
            });
            let camera = backCamera || frontCamera || cameras[0];
            let mirror = camera.name.indexOf('back') === -1;
            let scanner = new Instascan.Scanner({ video: document.getElementById('scanner'), scanPeriod: 5, mirror: mirror });
            scanner.addListener('scan', function (content) {
                $('#form-decode input[type=hidden][name=code]').val(content);
                $('#form-decode').submit();
            });
            scanner.start(camera);
        } else {
            $('.scanner-error').addClass('show').text('偵測不到攝影機');
            $('.scanner-loading').removeClass('show');
        }
    }).catch(function (e) {
        $('.scanner-error').addClass('show').text('錯誤！');
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
    .scanner-preview {
        height: 100%;
        color: #ffffff;
        background-color: #000000;
    }
    .scanner-preview .scanner-loading,
    .scanner-preview .scanner-error {
        height: 100%;
        display: none;
        justify-content: center;
        align-items: center;
    }
    .scanner-preview .scanner-loading.show,
    .scanner-preview .scanner-error.show {
        display: flex;
    }
    .scanner-preview #scanner {
        width: 100%;
        height: 100%;
        display: none;
    }
    .scanner-preview #scanner.show {
        display: block;
    }
    </style>
@endpush
