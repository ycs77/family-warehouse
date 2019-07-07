<div class="alert {{ $full ?? false ? 'alert-full' : '' }} alert-{{ $type ?? 'success' }} alert-dismissible fade show" role="alert">
    @switch($type ?? 'success')
        @case('success')
            <i class="fas fa-check-circle mr-1"></i>
            @break
        @case('danger')
            <i class="fas fa-times-circle mr-1"></i>
            @break
        @case('warning')
            <i class="fas fa-exclamation-circle mr-1"></i>
            @break
        @case('info')
            <i class="fas fa-info-circle mr-1"></i>
            @break
    @endswitch

    {{ $slot }}

    @if ($close ?? true)
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif
</div>
