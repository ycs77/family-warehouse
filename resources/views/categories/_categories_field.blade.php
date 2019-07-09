<div class="form-group row">
    <label for="name" class="col-lg-2 col-form-label text-lg-right required pt-0">
        {{ $label }}
    </label>

    <div class="col-lg-10">
        {{-- Root node --}}
        @if ($root ?? true)
            <div class="custom-control custom-radio">
                <input type="radio" name="parent_id" id="category-parent-root" class="custom-control-input @error('parent_id') is-invalid @enderror" value="" @if (!isset($parent_id)) checked @endif>
                <label for="category-parent-root" class="custom-control-label">(根節點)</label>
            </div>
        @endif

        {{-- Other nodes --}}
        @foreach ($categories as $category)
            @if ($current ? !$current->isSelfOrChild($category) : true)
                <div class="d-flex">
                    @for ($i = 0; $i < $category->depth; $i++)
                        <div class="indent"></div>
                    @endfor
                    <div class="custom-control custom-radio">
                        <input type="radio" name="parent_id" id="category-parent-{{ $category->id }}" class="custom-control-input @error('parent_id') is-invalid @enderror" value="{{ $category->id }}" @if ($parent_id === $category->id) checked @endif>
                        <label for="category-parent-{{ $category->id }}" class="custom-control-label">
                            {{ $category->name }}
                        </label>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
