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
use Tianwolf\FooterSetting\Models\FooterFriendlyLink;
class FooterFriendlyController extends Controller
{
    protected $types=[['id'=>0,'name'=>'友情链接'],['id'=>1,'name'=> '集团站群']];
    /**
     * 友情链接页面展示
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @return void
     */
    public function index(){
        return view('footer-setting::friendly.index');
    }

    /**
     * 添加友情链接页面展示
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $types = $this->types;
        return view('footer-setting::friendly.create',compact('types'));
    }

    /**
     * 编辑友情链接页面展示
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @return void
     */
    public function edit(Request $request,$id){
        $types = $this->types;
        $friendly = FooterFriendlyLink::whereNull('deleted_at')->find($id);
        if($friendly){
            return view('footer-setting::friendly.edit', compact('types','friendly'));
        }else{
            return redirect(route('admin.footer.friendly'))->with(['status' => '编辑失败！！']);
        }

    }

    /**
     * 获取友情链接数据
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @return void
     */
    public function lists(Request $request){
        $model = FooterFriendlyLink::query();

        if($request->get('title')){
            $title = $request->get('title');
            $model = $model->where('title','like',"%{$title}%");
        }

        $result = $model->whereNull('deleted_at')->paginate($request->get('limit', 30))->toArray();
        return response()->json([
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $result['total'],
            'data' => $result['data']
        ]);
    }

    /**
     * 友情链接数据存储操作
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @return void
     */
    public function storedAction(Request $request){
        $data = $request->only(['title','type', 'linkuri','avatar','sortnum']);
        $data['uid'] = auth('admin')->user()->id;
        $friendly = FooterFriendlyLink::create($data);
        if($friendly){
            return redirect(route('admin.footer.friendly'))->with(['status' => '添加成功']);
        }
        return redirect(route('admin.footer.friendly'))->with(['status' => '添加失败']);
    }

    /**
     * 友情链接数据更新操作
     * @author 杨鹏 <yangpeng1@dgg.net>
     * @param Request $request
     * @return void
     */
    public function updateAction(Request $request){
        $data = $request->only(['id', 'title','type', 'linkuri', 'avatar', 'sortnum']);
        $friendly = FooterFriendlyLink::whereNull('deleted_at')->findOrFail($data['id']);
        if ($friendly->update($data)) {
            return redirect(route('admin.footer.friendly'))->with(['status' => '更新成功']);
        }
        return redirect(route('admin.footer.friendly'))->withErrors(['status' => '系统错误']);
    }

    /**
     * 更改数据排序
     * @author 悟玄 <roc9574@sina.com>
     * @param Request $request
     * @return void
     */
    public function changeAction(Request $request){
        $id = $request->get('id');
        $field = $request->get('field');
        $value = $request->get('value');
        $module = FooterFriendlyLink::where('id', $id)->first();
        if ($module->update([$field=>$value])) {
            return response()->json(['code'=>0,'msg'=>'更新成功！！！']);
        } else {
            return response()->json(['code'=>1,'msg'=>'更新失败！！！']);
        }
    }

    /**
     * 更新业务模块状态操作
     * @author 悟玄 <roc9574@sina.com>
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
        $_status = Aptitude::where('id', $id)->first();
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
        $friendly = FooterFriendlyLink::whereNull('deleted_at')->whereIn('id',$ids);

        if (!$friendly) {
            return response()->json(['code' => 1, 'msg' => '请选择删除项']);
        }

        if ($friendly->delete()) {
            return response()->json(['code' => 0, 'msg' => '删除成功']);
        }
        return response()->json(['code' => 1, 'msg' => '删除失败']);
    }
    
}
