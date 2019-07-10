<?php

function current_route_query(array $query) {
    $name = request()->route()->getName();
    $attrs = array_merge(request()->query(), $query);
    return route($name, $attrs);
}
