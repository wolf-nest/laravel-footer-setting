<?php
/**
 * This file is part of the tian-wolf/laravel-footer-setting
 * (c) 天狼网络 <tian_wolf@sian.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Tianwolf\FooterSetting;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Tianwolf\FooterSetting\Models\FooterConfigs;
use Tianwolf\FooterSetting\Models\FooterFriendlyLink;
use Tianwolf\FooterSetting\Models\FooterNavMenus;

class FooterSettingServiceProvider extends ServiceProvider
{
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ]
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
    ];

    /**
     * Bootstrap the application services.
     * 
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views','footer-setting');
        // $this->//
        $this->setRoutes($this->app->router);
        $this->publishes([
            __DIR__.'/../resources/views'=>base_path('resources/views/vendor/footer-setting')
        ],'views');
        
        $this->publishes([__DIR__.'/../resources/assets' => public_path('static/vendor')], 'footer-setting');

        $this->publishes([
            __DIR__.'/../config/footer-setting.php' => config_path('footer-setting.php'),
        ], 'config');

        if (! class_exists('CreateFooterSettingTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__. '/../database/migrations/create_footer_setting_tables.php.stub' => database_path("/migrations/{$timestamp}_create_footer_setting_table.php"),
            ], 'migrations');
        }

        $this->show();

    }

    /**
     * Register the application services.
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @return void
     */
    public function register()
    {
        $this->registerRouteMiddleware();
        $this->mergeConfigFrom(__DIR__.'/../config/footer-setting.php', 'footer-setting');
    }

    /**
     * 设置页脚公共配置路由
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Router $router
     * @return void
     */
    private function setRoutes(Router $router){
        $attributes = [
            'prefix'        => config('footer-setting.route_setting.prefix'),
            'namespace'     =>'Tianwolf\FooterSetting\Http\Controllers',
            'middleware'    => config('footer-setting.route_setting.middleware'),
        ];
        
        $router->group($attributes,function($router){
            #页脚配置管理路由组 
            $router->group(['prefix'=>'configs','middleware'=>['permission:footer.configs.manage']], function ($router) {
                $router->get('/', 'FooterConfigsController@index')->name('admin.footer.configs');
                $router->put('/update', 'FooterConfigsController@updatedAction')->name('admin.footer.configs.update');
            });

            #页脚友情链接管理路由组 
            $router->group(['prefix'=>'friendly','middleware'=>['permission:footer.friendly.manage']], function ($router) {
                $router->get('/', 'FooterFriendlyController@index')->name('admin.footer.friendly');
                $router->get('/lists', 'FooterFriendlyController@lists')->name('admin.footer.friendly.lists');

                $router->get('/edit-{id?}', 'FooterFriendlyController@edit')->name('admin.footer.friendly.edite');
                $router->get('/create', 'FooterFriendlyController@create')->name('admin.footer.friendly.create');
                $router->post('/stored', 'FooterFriendlyController@storedAction')->name('admin.footer.friendly.stored');
                $router->put('/change', 'FooterFriendlyController@changeAction')->name('admin.footer.friendly.change');
                $router->put('/status', 'FooterFriendlyController@statusAction')->name('admin.footer.friendly.status');
                $router->put('/update', 'FooterFriendlyController@updateAction')->name('admin.footer.friendly.update');
                $router->delete('/destroy', 'FooterFriendlyController@destroy')->name('admin.footer.friendly.destroy');

            });

            #页脚导航菜单管理路由组 
            $router->group(['prefix'=>'navmenus','middleware'=>['permission:footer.navmenus.manage']], function ($router) {
                $router->get('/', 'FooterNavmenusController@index')->name('admin.footer.navmenus');
                $router->get('/lists', 'FooterNavmenusController@lists')->name('admin.footer.navmenus.lists');
                
                $router->get('/edit-{id?}', 'FooterNavmenusController@edit')->name('admin.footer.navmenus.edite');
                $router->get('/create', 'FooterNavmenusController@create')->name('admin.footer.navmenus.create');
                $router->post('/stored', 'FooterNavmenusController@storedAction')->name('admin.footer.navmenus.stored');
                $router->put('/change', 'FooterNavmenusController@changeAction')->name('admin.footer.navmenus.change');
                $router->put('/status', 'FooterNavmenusController@statusAction')->name('admin.footer.navmenus.status');
                $router->put('/update', 'FooterNavmenusController@updateAction')->name('admin.footer.navmenus.update');
                $router->delete('/destroy', 'FooterNavmenusController@destroy')->name('admin.footer.navmenus.destroy');
            });
        });
    }

    /**
     * 注册路由中间件
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        #register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        #register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }

    /**
     * 前台数据展示
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @return void
     */
    protected function show(){
        view()->composer('footer-setting::footer',function($view){
            $configs = FooterConfigs::select(['key','value'])->get();
            foreach ($configs as $key=>$val) {
                $configs[$val['key']] = $val['value'];
                unset($configs[$key]);
            }
            $friendly = FooterFriendlyLink::select(['title','linkuri','avatar'])->orderBy('sortnum','ASC')->get();
            $navmenus = FooterNavMenus::with(['childs'])->get();
            $footer = ['configs'=>$configs,'friendly'=>$friendly,'navmenus'=>$navmenus];
            $view->with('footer',$footer);
        });
    }
}
