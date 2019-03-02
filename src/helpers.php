<?php
/*
 * This file is part of the tian-wolf/laravel-footer-setting
 * 
 * (c) 天狼网络 <tian_wolf@sian.com>
 * 
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
use Tianwolf\FooterSetting\Models\FooterConfigs;
use Tianwolf\FooterSetting\Models\FooterFriendlyLink;
use Tianwolf\FooterSetting\Models\FooterNavMenus;
/**
 * 
 */
if (!function_exists('footer_configs')) {
    function footer_configs(){
        view()->composer('footer-setting::footer',function($view){
            $configs = FooterConfigs::select(['key','value'])->get();
            foreach ($configs as $key=>$val) {
                $configs[$val['key']] = $val['value'];
                unset($configs[$key]);
            }
            $view->with('configs',$configs);
        });
    }
}
/**
 * 
 */
if (!function_exists('footer_friendly')){
    function footer_friendly(){
        view()->composer('footer-setting::footer',function($view){
            $_friendly = FooterFriendlyLink::select(['type','title','linkuri','avatar'])->orderBy('sortnum','ASC')->get();
            $friendly = [];
            foreach ($_friendly as $key =>  $val) {
                if($val['type'] == 0){
                    $friendly['other'][$key]['title'] = $val['title'];
                    $friendly['other'][$key]['avatar'] = $val['avatar'];
                    $friendly['other'][$key]['linkuri']= $val['linkuri'];
                }elseif($val['type'] == 0){
                    $friendly['group'][$key]['title']= $val['title'];
                    $friendly['group'][$key]['avatar']= $val['avatar'];
                    $friendly['group'][$key]['linkuri'] = $val['linkuri'];
                }
            }
            $view->with('friendly',$friendly);
        });
    }
}

/**
 * 
 */
if (!function_exists('footer_navmenus')){
    function footer_navmenus(){
        view()->composer('footer-setting::footer',function($view){
            $navmenus = FooterNavMenus::with(['childs'])->where('parent_id',0)->get();
            $view->with('navmenus',$navmenus);
        });
    }
}