@if ($allChildren->count())
    <div class="row-children">
        <div class="form-group row">
            <label for="children" class="col-lg-2 col-form-label text-lg-right pt-0">代管小孩</label>
            <div class="col-lg-10">
                @foreach ($allChildren as $child)
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input @error('children') is-invalid @enderror" id="children_{{ $child->id }}" name="children[]" type="checkbox" value="{{ $child->id }}" @if($child->isExistsTo($user ?? null)) checked @endif>
                        <label for="children_{{ $child->id }}" class="custom-control-label">{{ $child->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if (isset($user) ? !$user->isCantDeprivation() : true)
        @push('script')
            <script>
            function changeRole() {
                const role = $('input[name=role]:checked').val();
                if (role === 'admin' || role === 'user') {
                    $('.row-children').show();
                } else {
                    $('.row-children').hide();
                }
            }
            $(window).on('load', changeRole);
            $('input[name=role]').change(changeRole);
            </script>
        @endpush

        @push('style')
            <style>
            .row-children {
                display: none;
            }
            </style>
        @endpush
    @endif
@endif
