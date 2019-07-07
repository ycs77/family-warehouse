<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($items as $index => $item)
        @if (count($items) !== $index + 1)
            <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
        @else
            <li class="breadcrumb-item active" aria-current="page">{{ $item['name'] }}</li>
        @endif
        @endforeach
    </ol>
</nav>
