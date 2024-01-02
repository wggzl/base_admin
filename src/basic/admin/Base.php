<?php

namespace Basic\Admin;

use Illuminate\Support\Facades\Facade;

class Base extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'abc';
    }
}
