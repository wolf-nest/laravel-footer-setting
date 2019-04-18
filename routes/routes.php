<?php
/**
 * This file is part of the tian-wolf/laravel-footer-setting
 * (c) 天狼网络 <tian_wolf@sian.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

#页脚公共配置路由组 ,'middleware'=>['permission:footer.setting.manage'] ,'middleware'=>'permission:footer.configs.manage'
Route::group(['namespace'=>'Tianwolf\FooterSetting\Http\Controllers','prefix'=>'admin/footer-setting','middleware'=>['web','auth','permission:footer.setting.manage'] ],function(){
    #页脚配置管理路由组
    Route::group(['prefix'=>'configs','middleware'=>['permission:footer.configs.manage']],function(){
        Route::get('/','FooterConfigsController@index')->name('admin.footer.configs');
        Route::put('/update', 'FooterConfigsController@updatedAction')->name('admin.footer.configs.update');

    });

    #页脚友情链接管理路由组
    Route::group(['prefix'=>'friendly','middleware'=>['permission:footer.friendly.manage']],function(){
        Route::get('/', 'FooterFriendlyController@index')->name('admin.footer.friendly');
        Route::get('/lists', 'FooterFriendlyController@lists')->name('admin.footer.friendly.lists');
        Route::get('/edit-{id?}', 'FooterFriendlyController@edit')->name('admin.footer.friendly.edite');
        Route::get('/create', 'FooterFriendlyController@create')->name('admin.footer.friendly.create');
        Route::post('/stored', 'FooterFriendlyController@storedAction')->name('admin.footer.friendly.stored');
        Route::put('/change', 'FooterFriendlyController@changeAction')->name('admin.footer.friendly.change');
        Route::put('/status', 'FooterFriendlyController@statusAction')->name('admin.footer.friendly.status');
        Route::put('/update', 'FooterFriendlyController@updateAction')->name('admin.footer.friendly.update');
        Route::delete('/destroy', 'FooterFriendlyController@destroy')->name('admin.footer.friendly.destroy');
        Route::put('/{id}/checknofollow/', 'FooterFriendlyController@checknofollow')->name('admin.footer.friendly.checknofollow');//是否开启nofollow
        
    });

    #页脚导航菜单管理路由组
    Route::group(['prefix'=>'navmenus','middleware'=>['permission:footer.navmenus.manage']],function(){
        Route::get('/', 'FooterNavmenusController@index')->name('admin.footer.friendly');
        Route::get('/lists', 'FooterNavmenusController@lists')->name('admin.footer.navmenus.lists');
                
        Route::get('/edit-{id?}', 'FooterNavmenusController@edit')->name('admin.footer.navmenus.edite');
        Route::get('/create', 'FooterNavmenusController@create')->name('admin.footer.navmenus.create');
        Route::post('/stored', 'FooterNavmenusController@storedAction')->name('admin.footer.navmenus.stored');
        Route::put('/change', 'FooterNavmenusController@changeAction')->name('admin.footer.navmenus.change');
        Route::put('/status', 'FooterNavmenusController@statusAction')->name('admin.footer.navmenus.status');
        Route::put('/update', 'FooterNavmenusController@updateAction')->name('admin.footer.navmenus.update');
        Route::delete('/destroy', 'FooterNavmenusController@destroy')->name('admin.footer.navmenus.destroy');
        put('/{id}/checknofollow/', 'FooterNavmenusController@checknofollow')->name('admin.footer.navmenus.checknofollow');//是否开启nofollow
    });
});