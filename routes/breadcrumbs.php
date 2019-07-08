<?php

Breadcrumbs::for('home', function ($trail) {
    $trail->push('首頁', route('home'));
});
