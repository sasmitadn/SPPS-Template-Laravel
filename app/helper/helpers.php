<?php

function is_active($routes)
{
    foreach ((array) $routes as $route) {
        if (request()->routeIs($route)) {
            return 'active';
        }
    }
    return '';
}