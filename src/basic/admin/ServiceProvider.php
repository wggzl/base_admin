<?php
namespace Basic\Admin;
use \Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind("abc", function () {
            return new User();
        });
    }
}
