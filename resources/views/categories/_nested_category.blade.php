@if ($category->isRoot())
<ul class="list-group list-nested">
@endif

    <li class="list-group-item list-group-child-item" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-has-child="{{ $category->children->count() ? 'true' : 'false' }}">
        @for ($i = 0; $i < $category->depth; $i++)
            <div class="indent"></div>
        @endfor

        <div>
            @if ($category->isRoot())
                <h4>
                    <a href="{{ route('category', $category) }}">{{ $category->name }}</a>
                </h4>
            @else
                <h6>
                    <a href="{{ route('category', $category) }}">{{ $category->name }}</a>
                </h6>
            @endif

            @if ($category->description)
                <p class="mb-2">{{ $category->description }}</p>
            @endif

            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-success">修改</a>
            <button class="btn btn-sm btn-danger btn-destroy">刪除</button>
        </div>
    </li>

    @foreach ($category->children as $child)
        @include('categories._nested_category', [
            'category' => $child,
        ])
    @endforeach

@if ($category->isRoot())
</ul>
@endif
