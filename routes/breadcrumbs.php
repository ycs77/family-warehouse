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
    $trail->push($user->name, route('users.show', $user));
});

Breadcrumbs::for('users.create', function ($trail) {
    $trail->parent('users.index');
    $trail->push('新增用戶', route('users.create'));
});

Breadcrumbs::for('users.edit', function ($trail, $user) {
    $trail->parent('users.show', $user);
    $trail->push('編輯用戶', route('users.edit', $user));
});

Breadcrumbs::for('users.password', function ($trail, $user) {
    $trail->parent('users.show', $user);
    $trail->push('修改密碼', route('users.password', $user));
});

Breadcrumbs::for('users.history.borrow', function ($trail, $user) {
    $trail->parent('users.show', $user);
    $trail->push('借物紀錄', route('users.history.borrow', $user));
});

Breadcrumbs::for('users.history.proxy', function ($trail, $user) {
    $trail->parent('users.show', $user);
    $trail->push('代借紀錄', route('users.history.proxy', $user));
});

Breadcrumbs::for('history.borrow', function ($trail) {
    $trail->parent('home');
    $trail->push('借物紀錄', route('history.borrow'));
});

Breadcrumbs::for('history.proxy', function ($trail) {
    $trail->parent('home');
    $trail->push('代借紀錄', route('history.proxy'));
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
    $trail->parent('categories.show', $category);
    $trail->push('編輯分類', route('categories.edit', $category));
});

// Item
Breadcrumbs::for('items.index', function ($trail) {
    $trail->parent('home');
    $trail->push('物品列表', route('items.index'));
});

Breadcrumbs::for('items.show', function ($trail, $item) {
    if ($item->category) {
        $trail->parent('categories.show', $item->category);
    } else {
        $trail->parent('home');
    }
    $trail->push($item->name, route('item', $item));
});

Breadcrumbs::for('items.create', function ($trail) {
    $trail->parent('items.index');
    $trail->push('新增物品', route('items.create'));
});

Breadcrumbs::for('items.edit', function ($trail, $item) {
    $trail->parent('items.show', $item);
    $trail->push('編輯物品', route('items.edit', $item));
});

Breadcrumbs::for('items.borrow', function ($trail, $item) {
    $trail->parent('items.show', $item);
    $trail->push('借出物品', route('items.borrow', $item));
});

Breadcrumbs::for('items.return', function ($trail, $item) {
    $trail->parent('items.show', $item);
    $trail->push('歸還物品', route('items.return', $item));
});

// Scanner
Breadcrumbs::for('scanner', function ($trail) {
    $trail->parent('home');
    $trail->push('掃描 QR code', route('scanner.index'));
});
