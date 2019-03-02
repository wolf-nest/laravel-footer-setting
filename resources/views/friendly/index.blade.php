@extends('admin.base')
@section('content')
<div class="layui-raw layui-row layui-col-space15">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('footer.friendly.manage.destroy')
                <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删除选中</button>
                @endcan
                @can('footer.friendly.manage.create')
                <a class="layui-btn layui-btn-sm" href="{{route('admin.footer.friendly.create')}}">添加链接</a>
                @endcan
                <button class="layui-btn layui-btn-sm" id="returnParent" level="1" pid="0"><i class="layui-icon layui-icon-left"></i>返回上级</button>
            </div>
            <div class="layui-input-inline"  style="width:240px;height:32px">
                <input style="height:32px" type="text" name="title" id="title" placeholder="请输入链接标题" class="layui-input">
            </div>
            <button class="layui-btn layui-btn-sm" id="friendlySearch">搜索</button>
        </div>
        <div class="layui-card-body">
            <table id="friendlyTable" lay-filter='friendlyTable'>
                <script typ="text/html" id="options">
                    @can('footer.friendly.manage.edit')
                    <button class="layui-btn layui-btn-sm" lay-event="update">编辑</button>
                    @endcan
                    @can('footer.friendly.manage.destroy')
                    <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="delete">删除</button>
                    @endcan
                </script>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    layui.use(['element','table'],function(){
        var table = layui.table;
        var element = layui.element;

        var friendlyTable = table.render({
            elem: '#friendlyTable'
            ,height: "full-200"
            ,url: "{{ route('admin.footer.friendly.lists') }}" //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {checkbox: true,fixed: true},
                {field: 'id', title: 'ID', sort: true,width:80}
                ,{field:'title',title:'链接标题'}
                ,{field:'type',title:'链接类型',align:'center',width:120,templet:function(d){
                    var type_text = ''
                    if(d.type == '0'){
                        type_text = '友情链接'
                    }else if(d.type == '1'){
                        type_text = '集团站群';
                    }
                    return type_text;
                }}
                ,{field:'linkuri',title:'菜单类型',align:'center',edit:'text'}
                ,{field:'sortnum',title:'菜单排序',align:'center',sort:true,edit:'text'}
                ,{field:'created_at',title:'添加时间',align:'center',sort:true}
                ,{field:'updated_at',title:'修改时间',align:'center',sort:true}
                ,{fixed: 'right',title:'操作', width: 240, align:'center', toolbar: '#options'}
            ]]
        });

        table.on('tool(friendlyTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
            var data = obj.data //获得当前行数据
                ,_event = obj.event; //获得 lay-event 对应的值
            if(_event === 'delete'){
                layer.confirm('确认删除吗？', function(index){
                    $.post("{{ route('admin.footer.friendly.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                        if (result.code==0){
                            obj.del(); //删除对应行（tr）的DOM结构
                        }
                        layer.close(index);
                        layer.msg(result.msg)
                    });
                });
            } else if(_event === 'update'){
                location.href = '/admin/footer-setting/friendly/edit-'+data.id;
            }
        });

        // 监听单元格编辑
        table.on('edit(friendlyTable)', function(obj){
            var value = obj.value //得到修改后的值
                ,data = obj.data //得到所在行所有键值
                ,field = obj.field; //得到字段
            var num_reg = /^[0-9]{1,2}$/
                ,linkuri = /(http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
            if(field == 'sortnum'){
                if(!num_reg.test(value)){
                    layer.msg("请输入正确的排序编号！！",{icon:5});
                    friendlyTable.reload();
                    return false;
                }
            }else if(field == 'linkuri'){
                if(!linkuri.test(value)){
                    layer.msg("请输入正确的跳转地址！！",{icon:5});
                    friendlyTable.reload()
                    return false;
                }
            }
            
            $.post('{{route('admin.footer.friendly.change')}}',{_method:'put','id':data.id,'field':field,'value':value},function(result){
                friendlyTable.reload()
            })
        });

        //批量删除
        $("#listDelete").click(function () {
            var ids = []
            var hasCheck = table.checkStatus('friendlyTable')
            var hasCheckData = hasCheck.data
            if (hasCheckData.length>0){
                $.each(hasCheckData,function (index,element) {
                    ids.push(element.id)
                })
            }
            if (ids.length>0){
                layer.confirm('确认删除吗？', function(index){
                    $.post("{{ route('admin.footer.friendly.destroy') }}",{_method:'delete',ids:ids},function (result) {
                        if (result.code==0){
                            friendlyTable.reload()
                        }
                        layer.close(index);
                        layer.msg(result.msg)
                    });
                })
            }else {
                layer.msg('请选择删除项')
            }
        })
    })
</script>
@endsection