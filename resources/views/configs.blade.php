@extends('admin.base')
@section('content')
<div class="layui-row layui-col-space15">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>站点配置</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.footer.configs.update')}}" method="post">
                {{csrf_field()}}
                {{method_field('put')}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">版权所有</label>
                    <div class="layui-input-block">
                        <input type="text" name="copyright" value="{{ $configs['copyright']??'' }}" lay-verify="required" placeholder="请输入站点版权信息" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">备案编号</label>
                    <div class="layui-input-block">
                        <input type="text" name="icp" value="{{ $configs['icp']??'' }}" lay-verify="required" placeholder="请输入站点ICP备案号" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">服务热线</label>
                    <div class="layui-input-block">
                        <input type="text" name="service_hotline" value="{{ $configs['service_hotline']??'' }}" lay-verify="required" placeholder="请输入站点服务热线" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">覆盖城市</label>
                    <div class="layui-input-block">
                        <input type="text" name="service_citys" value="{{ $configs['service_citys']??'' }}" lay-verify="required" placeholder="请输入站点服务城市" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
                            <div class="layui-upload-list" >
                                <ul id="layui-upload-box" class="layui-clear">
                                    @if(isset($configs['thumb']))
                                        <li><img src="{{ $configs['thumb'] }}" /><p>上传成功</p></li>
                                    @endif
                                </ul>
                                <input type="hidden" name="thumb" id="thumb" value="{{ $configs['thumb']??'' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<style>
    #layui-upload-box li{
        width: 120px;
        height: 100px;
        float: left;
        position: relative;
        overflow: hidden;
        margin-right: 10px;
        border:1px solid #ddd;
    }
    #layui-upload-box li img{
        width: 100%;
    }
    #layui-upload-box li p{
        width: 100%;
        height: 22px;
        font-size: 12px;
        position: absolute;
        left: 0;
        bottom: 0;
        line-height: 22px;
        text-align: center;
        color: #fff;
        background-color: #333;
        opacity: 0.6;
    }
    #layui-upload-box li i{
        display: block;
        width: 20px;
        height:20px;
        position: absolute;
        text-align: center;
        top: 2px;
        right:2px;
        z-index:999;
        cursor: pointer;
    }
</style>
<script>
    layui.use(['upload'],function () {
        var upload = layui.upload
        //普通图片上传
        var uploadInst = upload.render({
            elem: '#uploadPic'
            ,url: '{{ route("uploadImg") }}'
            ,multiple: false
            ,data:{"_token":"{{ csrf_token() }}"}
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                /*obj.preview(function(index, file, result){
                 $('#layui-upload-box').append('<li><img src="'+result+'" /><p>待上传</p></li>')
                 });*/
                obj.preview(function(index, file, result){
                    $('#layui-upload-box').html('<li><img src="'+result+'" /><p>上传中</p></li>')
                });

            }
            ,done: function(res){
                //如果上传失败
                if(res.code == 0){
                    $("#thumb").val(res.url);
                    $('#layui-upload-box li p').text('上传成功');
                    return layer.msg(res.msg);
                }
                return layer.msg(res.msg);
            }
        });
    })
</script>
@endsection