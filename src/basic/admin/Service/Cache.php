<?php
namespace Basic\Admin\Service;
use Illuminate\Support\Facades\Cache as LaravelCache;

class Cache
{
    protected $store;
    public function store($store = null)
    {
        if (!$store) {
            $store = config('baseAdmin.cache.store', 'default');
            $store = ('default' == $store) ? null : $store;
        }
        $this->store = LaravelCache::store($store);

        return $this->store;
    }
}
