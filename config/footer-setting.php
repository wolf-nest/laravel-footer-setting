<?php
/*
 * This file is part of the tian-wolf/laravel-footer-setting
 * 
 * (c) 天狼网络 <tian_wolf@sian.com>
 * 
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
return [
    /*
     * 数据表配置
     */
    'table_names'=>[
        'configs' =>'footer_config',
        'friendly'=>'footer_friendly_link',
        'navmenus'=>'footer_nav_menu',
    ],

    /*
     * 路由设置配置
     */
    'route_setting'=>[
        'prefix'=>'admin',
        'middleware'=>['web','auth']
    ],

    /**
     * 缓存配置
     */
    'cache' => [
        /*
         * By default all footer setting will be cached for 24 hours unless a footer setting
         * is updated. Then the cache will be flushed immediately.
         */

        'expiration_time' => 60 * 24,

        /*
         * The key to use when tagging and prefixing entries in the cache.
         */

        'key' => 'tianwolf.footersetting.cache',

        /*
         * 
         */
        'model_key' => 'name',

        /*
         * You may optionally indicate a specific cache driver to use for footer setting
         * caching using any of the `store` drivers listed in the cache.php config
         * file. Using 'default' here means to use the `default` set in cache.php.
         */
        'store' => 'default',
    ],
];