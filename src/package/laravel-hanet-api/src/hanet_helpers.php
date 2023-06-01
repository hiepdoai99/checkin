<?php

if (!function_exists('laravel_hanet')) {
    function laravel_hanet()
    {
        return app(\Htqxd\LaravelHanetApi\LaravelHanet::class);
    }
}

if (!function_exists('hanet')) {
    function hanet()
    {
        return laravel_hanet()->getHanet();
    }
}