<div class="layui-form-item">
    <label for="" class="layui-form-label">链接类型</label>
    <div class="layui-input-inline">
        <select name="type" lay-verify="required">
            <option value=""></option>
            @foreach($types as $type)
                <option value="{{ $type['id'] }}" @if(isset($friendly->type)&&$friendly->type==$type['id'])selected @endif >{{ $type['name'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">链接标题</label>
    <div class="layui-input-inline">
        <input type="text" name="title" value="{{$friendly->title??old('title')}}" lay-verify="required" placeholder="请输入链接标题必填" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">跳转链接</label>
    <div class="layui-input-inline">
        <input type="text" name="linkuri" value="{{$friendly->linkuri??old('linkuri')}}" lay-verify="" placeholder="请输入跳转链接非必填" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">展示图片</label>
    <div class="layui-input-inline">
        <input type="text" name="avatar" value="{{$friendly->avatar??old('avatar')}}" lay-verify="" placeholder="请输入展示图片非必填" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">排序编号</label>
    <div class="layui-input-inline">
        <input type="text" name="sortnum" value="{{($friendly->sortnum??old('sortnum'))??99}}" lay-verify="number" placeholder="请输入排序编号" class="layui-input" >
    </div>
</div>


<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <a  class="layui-btn" href="{{route('admin.footer.friendly')}}" >返 回</a>
    </div>
</div>