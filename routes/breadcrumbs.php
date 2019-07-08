<?php

Breadcrumbs::for('home', function ($trail) {
    $trail->push('首頁', route('home'));
});

Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('home');
    $trail->push('用戶管理', route('users.index'));
});

Breadcrumbs::for('users.create', function ($trail) {
    $trail->parent('users.index');
    $trail->push('新增用戶', route('users.create'));
});

Breadcrumbs::for('users.show', function ($trail, $user) {
    $trail->parent('users.index');
    $trail->push('用戶 ' . $user->name, route('users.show', $user));
});

Breadcrumbs::for('users.edit', function ($trail, $user) {
    $trail->parent('users.index');
    $trail->push('編輯用戶', route('users.edit', $user));
});

Breadcrumbs::for('users.password', function ($trail, $user) {
    $trail->parent('users.index');
    $trail->push('修改用戶密碼', route('users.password', $user));
});
