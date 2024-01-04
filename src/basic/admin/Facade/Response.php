<?php
namespace Basic\Admin\Facade;
use Illuminate\Support\Facades\Facade;

class Response extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'base-admin.response';
    }
}
