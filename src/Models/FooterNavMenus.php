<?php
/**
 * This file is part of the tian-wolf/laravel-footer-setting
 * (c) 天狼网络 <tian_wolf@sian.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Tianwolf\FooterSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FooterNavMenus extends Model
{
    use SoftDeletes;
    /**
     * 定义数据表名
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @var string
     */
    protected $table = 'footer_nav_menu';

    /**
     * 受保护的字段
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 可更改的字段
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @var array
     */
    protected $fillable = ['parent_id','type','title','linkuri','sortnum','is_open_nofollow','deleted_at'];

    /**
     * 获取子菜单栏目
     * @author 杨鹏 <yangpeng1@dgg.net>
     */
    public function childs(){
        return $this->hasMany('Tianwolf\FooterSetting\Models\FooterNavMenus','parent_id','id');
    }
}
