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
use Tianwolf\FooterSetting\Models\FooterNavMenus;


class FooterNavmenusController extends Controller
{

    protected $types = [
        ['key'=>'default','name'=>'默认类型'],
        ['key'=>'jump','name'=>'跳转链接'],
        ['key'=>'image','name'=>'展示图片']
    ];

    /**
     * 菜单管理页面
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @return void
     */
    public function index()
    {
        return view('footer-setting::navmenus.index');
    }

    /**
     * 创建新的菜单栏目
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @return void
     */
    public function create(){
        $types = $this->types;
        $parents = FooterNavMenus::whereNull('deleted_at')
            ->where('parent_id',0)
            ->select(['id','title'])
            ->get();
        return view('footer-setting::navmenus.create',compact('parents','types'));
    }

    /**
     * 编辑原有菜单栏目
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function edit(Request $request,$id){
        $types = $this->types;
        $parents = FooterNavMenus::whereNull('deleted_at')
            ->where('parent_id', 0)
            ->select(['id', 'title'])
            ->get();
        $navmenus = FooterNavMenus::whereNull('deleted_at')->findOrFail($id);
        return view('footer-setting::navmenus.edit', compact('parents','types','navmenus'));
    }

    /**
     * 读取菜单栏目数据
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @return void
     */
    public function lists(Request $request){
        $model = FooterNavMenus::query();

        if($request->get('title')){
            $title = $request->get('title');
            $model = $model->where('title','like',"%{$title}%");
        }

        $result = $model->whereNull('deleted_at')->where('parent_id',$request->get('parent_id', 0))->paginate($request->get('limit', 30))->toArray();
        return response()->json([
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $result['total'],
            'data' => $result['data']
        ]);
    }

    /**
     * 存储菜单栏目数据
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @return void
     */
    public function storedAction(Request $request){
        $data = $request->only(['parent_id','title', 'type','linkuri','sortnum','switch']);
        $data['is_open_nofollow'] = isset($data['switch']) && $data['switch'] == 'on' ? 1 : 0 ;
        $navmenus = FooterNavMenus::create($data);
        if ($navmenus) {
            return redirect(route('admin.footer.navmenus'))->with(['status' => '添加成功']);
        }
        return redirect(route('admin.footer.navmenus'))->with(['status' => '添加失败']);
    }

    /**
     * 更新菜单栏目数据
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @return void
     */
    public function updateAction(Request $request){
        $data = $request->only(['id', 'parent_id', 'title', 'type', 'linkuri', 'sortnum','is_open_nofollow','switch']);
        $navmenus = FooterNavMenus::whereNull('deleted_at')->findOrFail($data['id']);
        $data['is_open_nofollow'] = isset($data['switch']) && $data['switch'] == 'on' ? 1 : 0 ;
        if ($navmenus->update($data)) {
            return redirect(route('admin.footer.navmenus'))->with(['status' => '更新成功']);
        }
        return redirect(route('admin.footer.navmenus'))->withErrors(['status' => '系统错误']);
    }

    /**
     * 更改数据排序
     * @author 杨鹏 <roc9574@sina.com>
     * @param Request $request
     * @return void
     */
    public function changeAction(Request $request){
        $id = $request->get('id');
        $field = $request->get('field');
        $value = $request->get('value');
        $module = FooterNavMenus::whereNull('deleted_at')->where('id', $id)->first();
        if ($module->update([$field=>$value])) {
            return response()->json(['code'=>0,'msg'=>'更新成功！！！']);
        } else {
            return response()->json(['code'=>1,'msg'=>'更新失败！！！']);
        }
    }

    /**
     * 更新业务模块状态操作[发布、首推、热推]
     * @author 杨鹏 <roc9574@sina.com>
     * @param Request $request
     * @return void
     */
    public function statusAction(Request $request){
        $id = $request->get('id');
        $status = $request->get('status');
        $action = $request->get('action');
        if (!in_array($status, [0,1])) {
            return response()->json(['code' => 1, 'msg' => '不合法的参数']);
        }
        $_status = FooterNavMenus::whereNull('deleted_at')->where('id', $id)->first();
        if (!$_status) {
            return response()->json(['code'=>1,'msg'=>'数据不存在']);
        }
        if ($_status->update([$action=>$status])) {
            return response()->json(['code'=>0,'msg'=>'状态更新成功！！！！']);
        } else {
            return response()->json(['code'=>1,'msg'=>'状态更新失败！！！！']);
        }
    }

    /**
     * 删除友情链接
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)) {
            return response()->json(['code' => 1, 'msg' => '请选择删除项']);
        }
        $navmenus = FooterNavMenus::whereNull('deleted_at')->whereIn('id', $ids);

        if (!$navmenus) {
            return response()->json(['code' => 1, 'msg' => '请选择删除项']);
        }

        if ($navmenus->delete()) {
            
            return response()->json(['code' => 0, 'msg' => '删除成功']);
        }
        return response()->json(['code' => 1, 'msg' => '删除失败']);
    }
    
    /**
     * 列表页面是否开启nofollow功能
     * @author chenshubo@dgg.net
     * @param Request $request
     * @return void
     */
    public function checknofollow(Request $request,$id)
    {
        $status = $request->get('status');
    
        if (empty($id) || !is_numeric($id) ) {
            return response()->json(['code' => 1, 'msg' => '请求参数错误']);
        }
        $friendly = FooterNavMenus::where('id',$id);
    
        if ( empty($friendly) ) {
            return response()->json(['code' => 1, 'msg' => '当前信息不存在']);
        }
    
        if ($friendly->update(['is_open_nofollow'=> ($status == 1 ? 0 : 1)])) {
            return response()->json(['code' => 0, 'msg' => '操作成功']);
        }
        return response()->json(['code' => 1, 'msg' => '操作失败']);
    }
}
