# laravel-footer-setting

<h1 align="center">Laravel Footer Setting</h1>

<p align="center"></p>
[![Latest Stable Version](https://poser.pugx.org/tian-wolf/laravel-footer-setting/v/stable)](https://packagist.org/packages/tian-wolf/laravel-footer-setting)
[![Total Downloads](https://poser.pugx.org/tian-wolf/laravel-footer-setting/downloads)](https://packagist.org/packages/tian-wolf/laravel-footer-setting)
[![Latest Unstable Version](https://poser.pugx.org/tian-wolf/laravel-footer-setting/v/unstable)](https://packagist.org/packages/tian-wolf/laravel-footer-setting)
[![License](https://poser.pugx.org/tian-wolf/laravel-footer-setting/license)](https://packagist.org/packages/tian-wolf/laravel-footer-setting)
[![Monthly Downloads](https://poser.pugx.org/tian-wolf/laravel-footer-setting/d/monthly)](https://packagist.org/packages/tian-wolf/laravel-footer-setting)
[![Daily Downloads](https://poser.pugx.org/tian-wolf/laravel-footer-setting/d/daily)](https://packagist.org/packages/tian-wolf/laravel-footer-setting)


## 运行环境

- php >= 7.0
- composer
- laravel || lumen >= 5.1

## 如何安装

```Shell
$ composer require tian-wolf/laravel-footer-setting
```

### 添加 service provider（optional. if laravel < 5.5 || lumen）

```PHP
// laravel < 5.5
Tianwolf\FooterSetting\FooterSettingServiceProvider::class


// lumen
$app->register(Tianwolf\FooterSetting\FooterSettingServiceProvider::class);
```

### 添加 alias（optional. if laravel < 5.5）

```PHP
'FooterSetting'=>Tianwolf\FooterSetting\Facades\FooterSetting::class,
```

### 配置文件&数据表生成

```Shell
$ php artisan vendor:publish --provider="Tianwolf\FooterSetting\FooterSettingServiceProvider" 
```

**lumen 用户请手动复制**

随后，请在 `config` 文件夹中完善配置信息。

## 添加权限数据 至

```PHP
[
    'name' => 'footer.setting.manage',
    'display_name' => '公共页脚管理',
    'route' => '',
    'icon_id' => '19',
    'child' => [
        [
            'name' => 'footer.configs.manage',
            'display_name' => '页脚配置管理',
            'route' => 'admin.footer.configs',//admin.footer.configs
            'icon_id' => '13',
            'child' => []
        ],
        [
            'name' => 'footer.friendly.manage',
            'display_name' => '友情链接管理',
            'route' => 'admin.footer.friendly',//admin.footer.friendly
            'icon_id' => '124',
            'child' => [
                ['name' => 'footer.friendly.manage.create', 'display_name' => '创建友情链接', 'route' => 'admin.footer.friendly.create'],
                ['name' => 'footer.friendly.manage.edit', 'display_name' => '编辑友情链接', 'route' => 'admin.footer.friendly.edit'],
                ['name' => 'footer.friendly.manage.destroy', 'display_name' => '删除友情链接', 'route' => 'admin.footer.friendly.destroy'],
            ]
        ],
        [
            'name' => 'footer.navmenus.manage',
            'display_name' => '导航菜单管理',
            'route' => 'admin.footer.navmenus',//admin.footer.navmenus
            'icon_id' => '124',
            'child' => [
                ['name' => 'footer.navmenus.manage.create', 'display_name' => '创建导航菜单', 'route' => 'admin.footer.navmenus.create'],
                ['name' => 'footer.navmenus.manage.edit', 'display_name' => '编辑导航菜单', 'route' => 'admin.footer.navmenus.edit'],
                ['name' => 'footer.navmenus.manage.destroy', 'display_name' => '删除导航菜单', 'route' => 'admin.footer.navmenus.destroy'],
            ]
        ]
    ]
]
```
## 如何使用
在项目文件夹下找到：app/Providers/AppServiceProvider.php 文件 在里面添加 如下配置：
```PHP
use Tianwolf\FooterSetting\Models\FooterConfigs;
use Tianwolf\FooterSetting\Models\FooterFriendlyLink;
use Tianwolf\FooterSetting\Models\FooterNavMenus;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        #以上为其它内容
        ......
        #以下为添加内容
        view()->composer('vendor.footer-setting.footer',function($view){
            $configs = FooterConfigs::select(['key','value'])->get();
            foreach ($configs as $key=>$val) {
                $configs[$val['key']] = $val['value'];
                unset($configs[$key]);
            }
            $_friendly = FooterFriendlyLink::select(['type','title','linkuri','avatar'])->orderBy('sortnum','ASC')->get();
            $friendly = [];
            foreach ($_friendly as $key =>  $val) {
                if($val['type'] == 0){
                    $friendly['other'][$key]['title'] = $val['title'];
                    $friendly['other'][$key]['avatar'] = $val['avatar'];
                    $friendly['other'][$key]['linkuri']= $val['linkuri'];
                }elseif($val['type'] == 1){
                    $friendly['group'][$key]['title']= $val['title'];
                    $friendly['group'][$key]['avatar']= $val['avatar'];
                    $friendly['group'][$key]['linkuri'] = $val['linkuri'];
                }
            }
            $navmenus = FooterNavMenus::with(['childs'])->where('parent_id',0)->get();
            $footer = ['configs'=>$configs,'friendly'=>$friendly,'navmenus'=>$navmenus];
            $view->with('footer',$footer);
        });
    }
}

在前端基础模版中引入footer 模版 与css样式
<link rel="stylesheet" type="text/css" media="screen" href="/static/vendor/footer.css" />
@include('vendor.footer-setting.footer')
```

具体使用说明请传送至 [https://github.com/tian-wolf/laravel-footer-setting](https://github.com/tian-wolf/laravel-footer-setting)

## LICENSE [MIT](https://github.com/tian-wolf/laravel-footer-setting/blob/master/LICENSE)
