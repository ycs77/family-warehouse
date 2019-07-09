@switch($role)
    @case('admin')
        <span class="badge badge-pill badge-danger">管理員</span>
        @break
    @case('child')
        <span class="badge badge-pill badge-warning">小孩</span>
        @break
    @case('user')
    @default
        <span class="badge badge-pill badge-success">用戶</span>
@endswitch
