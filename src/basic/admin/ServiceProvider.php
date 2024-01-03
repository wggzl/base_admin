<?php
namespace Basic\Admin;
use Basic\Admin\Captcha\Captcha;
use Basic\Admin\Support\Loader;
use Illuminate\Foundation\AliasLoader;
use \Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    //别名
    protected $alias = [
        'ResponseCode' => Http\ResponseCode::class,
    ];

    public function register()
    {
        //配置
        $this->registerConfig();
        //别名
        $this->registerAlias();
        //绑定暂缓
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
    }



}
