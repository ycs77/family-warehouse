@switch($action)
    @case('borrow')
        <span class="badge badge-primary">借出</span>
        @break
    @case('return')
        <span class="badge badge-success">歸還</span>
        @break
@endswitch
