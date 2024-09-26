<?php

use Illuminate\Support\Str;

if (!function_exists('generateRandomCode')) {
    function generateRandomCode(): string
    {
        $code = Str::random(10) . time();
        return $code;
    }
}
