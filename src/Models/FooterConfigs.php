<?php
/**
 * This file is part of the tian-wolf/laravel-footer-setting
 * (c) 天狼网络 <tian_wolf@sian.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Tianwolf\FooterSetting\Models;

use Illuminate\Database\Eloquent\Model;


class FooterConfigs extends Model
{
    /**
     * 定义数据表名
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @var string
     */
    protected $table = 'footer_config';

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
    protected $fillable = ['key','value'];
}
