@if (session('status'))
    @alert([
        'type' => session('status')['type'] ?? 'success',
        'full' => $full ?? false,
    ])
        {{ session('status')['message'] }}
    @endalert
@endif
