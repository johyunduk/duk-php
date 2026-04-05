<?php

use Debugger\Duk\Duk;

if (! function_exists('duk')) {
    function duk(mixed ...$values): Duk
    {
        return Duk::create(...$values);
    }
}
