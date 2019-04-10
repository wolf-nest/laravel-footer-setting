<div class="layui-form-item">
    <label for="" class="layui-form-label">链接类型</label>
    <div class="layui-input-inline">
        <select name="parent_id" lay-verify="required">
            <option value="0">顶级菜单</option>
            @foreach($parents as $parent)
                <option value="{{ $parent['id'] }}" @if(isset($navmenus->parent_id)&&$navmenus->parent_id==$parent['id'])selected @endif >{{ $parent['title'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">菜单名称</label>
    <div class="layui-input-inline">
        <input type="text" name="title" value="{{$navmenus->title??old('title')}}" lay-verify="required" placeholder="请输入链接标题必填" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">菜单类型</label>
    <div class="layui-input-inline">
        <select name="type" lay-verify="required">
            @foreach($types as $type)
                <option value="{{ $type['key'] }}" @if(isset($navmenus->type)&&$navmenus->type==$type['key'])selected @endif >{{ $type['name'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- 展示图片上传 -->
<!--div class="layui-form-item">
    <label for="" class="layui-form-label" ></label>
</div-->
<!-- /展示图片上传-->

<div class="layui-form-item">
    <label for="" class="layui-form-label">相关链接</label>
    <div class="layui-input-inline">
        <input type="text" name="linkuri" value="{{$navmenus->linkuri??old('linkuri')}}" lay-verify="" placeholder="请输入跳转链接/图片链接非必填" class="layui-input" >
    </div>
</div>



<div class="layui-form-item">
    <label for="" class="layui-form-label">启用nofollow</label>
    <div class="layui-input-block">
        <input type="hidden" name="is_open_nofollow" id="is_open_nofollow" value="@if(isset($navmenus->is_open_nofollow)){{$navmenus->is_open_nofollow}}@else 0 @endif">
        @if( isset($navmenus->is_open_nofollow) && $navmenus->is_open_nofollow == 1 )
            <input type="checkbox" name="switch" lay-skin="switch" lay-text="启用|未启用" lay-filter="isShow" checked >
        @else
            <input type="checkbox" name="switch" lay-skin="switch" lay-text="启用|未启用" lay-filter="isShow" >
        @endif
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">排序编号</label>
    <div class="layui-input-inline">
        <input type="text" name="sortnum" value="{{($navmenus->sortnum??old('sortnum'))??99}}" lay-verify="number" placeholder="请输入排序编号" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.footer.navmenus')}}" >返 回</a>
    </div>
</div>