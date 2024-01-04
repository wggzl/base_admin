<?php
namespace Basic\Admin;
use Basic\Admin\Captcha\Captcha;
use Basic\Admin\Service\Cache;
use Basic\Admin\Support\Loader;
use Illuminate\Foundation\AliasLoader;
use \Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    //别名
    protected $alias = [
        'ResponseCode' => Http\ResponseCode::class,
    ];

    /**
     * 路由中间件
     *
     * @var array
     */
    protected $routeMiddleware = [
        'basic-admin.auth' => Middleware\Authenticate::class,
        'basic-admin.admin-auth' => Middleware\AdminCheck::class,
        'basic-admin.permission' => Middleware\Permission::class,
    ];

    /**
     * 中间件分组
     *
     * @var array
     */
    protected $middlewareGroups = [
        'basic-admin' => [
            'basic-admin.auth',
            'basic-admin.permission',
        ],
    ];

    public function register()
    {
        //配置
        $this->registerConfig();
        //别名
        $this->registerAlias();
        //绑定
        $this->registerBind();
        //推送
        $this->registerPublishing();
        //注册路由中间件
        $this->registerRouteMiddleware();
    }

    //配置
    protected function registerConfig()
    {
        //配置
        $this->mergeConfigFrom(
            __DIR__ . '/../resources/config/baseAdmin.php', 'baseAdmin'
        );
        //语言包 暂缓
        //路由
        $this->loadRoutesFrom(__DIR__ . '/../resources/routes/admin.php');
    }

    //别名
    protected function registerAlias()
    {
        $aliasLoader = AliasLoader::getInstance();
        foreach ($this->alias as $alias => $class) {
            $aliasLoader->alias($alias, $class);
        }
    }

    //绑定
    protected function registerBind()
    {
        //加载器
        $this->app->bind('base-admin.loader', Loader::class);
        //验证码 相关的类还没有补充完整
        $this->app->bind('base-admin.captcha', Contracts\Captcha::class);
        $this->app->bind(Contracts\Captcha::class, function () {
            $captcha = new Captcha();
            $config = config('baseAdmin.captcha');
            $config = collect($config)
                ->filter(function($data) {
                    return !empty($data);
                })
                ->toArray();
            $captcha->withConfig($config);
            return $captcha;
        });

        //响应
        $this->app->bind('base-admin.response', Contracts\Response::class);
        $this->app->bind(Contracts\Response::class, function () {
            $httpResponse = new Http\Response();
            $config = config('baseAdmin.response.json');
            $httpResponse->withIsAllowOrigin($config['is_allow_origin'])
                ->withAllowOrigin($config['allow_origin'])
                ->withAllowMethods($config['allow_methods'])
                ->withMaxAge($config['max_age'])
                ->withAllowHeaders($config['allow_headers'])
                ->withExposeHeaders($config['expose_headers'])
                ->withAllowCredentials($config['allow_credentials']);
            return $httpResponse;
        });

        //缓存
        $this->app->singleton('base-admin.cache', function () {
            $cache = new Cache();
            return $cache->store();
        });

        //暂缓
    }

    //推送
    protected function registerPublishing() {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/config' => config_path()
            ], 'base-admin-config');
        }
    }

    //中间件
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }






}
