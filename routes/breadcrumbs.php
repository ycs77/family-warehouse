<?php

Breadcrumbs::for('home', function ($trail) {
    $trail->push('首頁', route('home'));
});

// User
Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('home');
    $trail->push('用戶管理', route('users.index'));
});

Breadcrumbs::for('users.show', function ($trail, $user) {
    $trail->parent('users.index');
    $trail->push('用戶 ' . $user->name, route('users.show', $user));
});

Breadcrumbs::for('users.create', function ($trail) {
    $trail->parent('users.index');
    $trail->push('新增用戶', route('users.create'));
});

Breadcrumbs::for('users.edit', function ($trail, $user) {
    $trail->parent('users.index');
    $trail->push('編輯用戶', route('users.edit', $user));
});

Breadcrumbs::for('users.password', function ($trail, $user) {
    $trail->parent('users.index');
    $trail->push('修改用戶密碼', route('users.password', $user));
});

// Category
Breadcrumbs::for('categories.index', function ($trail) {
    $trail->parent('home');
    $trail->push('分類管理', route('categories.index'));
});

Breadcrumbs::for('categories.show', function ($trail, $category) {
    $trail->parent('home');
    foreach ($category->ancestors as $parent) {
        $trail->push($parent->name, route('category', $parent));
    }
    $trail->push($category->name, route('category', $category));
});

Breadcrumbs::for('categories.create', function ($trail) {
    $trail->parent('categories.index');
    $trail->push('新增分類', route('categories.create'));
});

Breadcrumbs::for('categories.edit', function ($trail, $category) {
    $trail->parent('categories.index');
    $trail->push('編輯分類', route('categories.edit', $category));
});
