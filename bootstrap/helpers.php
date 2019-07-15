<?php

function current_route_query(array $query) {
    $name = request()->route()->getName();
    $attrs = array_merge(request()->query(), $query);
    return route($name, $attrs);
}

function items_search_route() {
    $name = request()->route()->getName();
    $attrs = $name === 'items.index' ? request()->query() : [];
    return route('items.index', $attrs);
}
