<?php
/**
 * This file is part of the tian-wolf/laravel-footer-setting
 * (c) 天狼网络 <tian_wolf@sian.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tianwolf\FooterSetting\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tianwolf\FooterSetting\Models\FooterConfigs;

class FooterConfigsController extends Controller
{
    /**
     * 访问页脚配置
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @return view
     */
    public function index(){
        $configs = FooterConfigs::pluck('value', 'key');
        return view('footer-setting::configs', compact('configs'));
    }

    /**
     * 更新页脚配置内容
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @return void
     */
    public function updatedAction(Request $request){
        $data = $request->except(['file','_token', '_method']);
        if (empty($data)) {
            return back()->withErrors(['status' => '无数据更新']);
        }
        FooterConfigs::truncate();
        foreach ($data as $key => $val) {
            FooterConfigs::create([
                'key' => $key,
                'value' => $val
            ]);
        }
        return back()->with(['status' => '更新成功']);
    }
}
