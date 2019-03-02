@extends('admin.base')
@section('content')
<div class="layui-raw layui-row layui-col-space15">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('footer.navmenus.manage.destroy')
                <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删除选中</button>
                @endcan
                @can('footer.navmenus.manage.create')
                <a class="layui-btn layui-btn-sm" href="{{route('admin.footer.navmenus.create')}}">添加链接</a>
                @endcan
                <button class="layui-btn layui-btn-sm" id="returnParent" level="1" pid="0"><i class="layui-icon layui-icon-left"></i>返回上级</button>
            </div>
            <div class="layui-input-inline"  style="width:240px;height:32px">
                <input style="height:32px" type="text" name="title" id="title" placeholder="请输入链接标题" class="layui-input">
            </div>
            <button class="layui-btn layui-btn-sm" id="friendlySearch">搜索</button>
        </div>
        <div class="layui-card-body">
            <table id="navmenusTable" lay-filter="navmenusTable">
                <script typ="text/html" id="options">
                    <button class="layui-btn layui-btn-sm" lay-event="children">子菜单</button>
                    @can('footer.navmenus.manage.edit')
                    <button class="layui-btn layui-btn-sm" lay-event="update">编辑</button>
                    @endcan
                    @can('footer.navmenus.manage.destroy')
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
        layui.use(['layer','table','form'],function () {
            var layer = layui.layer;
            var form = layui.form;
            var table = layui.table;
            //用户表格初始化
            var navmenusTable = table.render({
                elem: '#navmenusTable'
                ,height: "full-200"
                ,url: "{{ route('admin.footer.navmenus.lists') }}" //数据接口
                ,page: true //开启分页
                ,cols: [[ //表头
                    {checkbox: true,fixed: true},
                    {field: 'id', title: 'ID', sort: true,width:80}
                    ,{field:'title',title:'菜单名称',align:'center',edit:'text'}
                    ,{field:'type',title:'菜单类型',align:'center',width:120,templet:function(d){
                        var type_text = '';
                        if(d.type =='default'){
                            type_text = '默认类型';
                        }else if(d.type == 'jump'){
                            type_text = '链接跳转';
                        }else if(d.type == 'image'){
                            type_text = '图片展示';
                        }
                        return type_text
                    }}
                    ,{field:'linkuri',title:'跳转或图片链接',align:'center',width:180,templet:function(d){
                        if(d.type == 'default'){
                            return 'javascript:void(0);'
                        }else{
                            return "<a href='"+d.linkuri+"' target='_blank'>点击查看</a>"
                        }
                    }}
                    ,{field:'sortnum',title:'菜单排序',align:'center',width:120,sort:true,edit:'text'}
                    ,{field:'created_at',title:'添加时间',align:'center',sort:true}
                    ,{fixed: 'right',title:'操作', width: 200, align:'center', toolbar: '#options'}

                ]]
            });

            table.on('tool(navmenusTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data //获得当前行数据
                    ,_event = obj.event; //获得 lay-event 对应的值
                if(_event === 'delete'){
                    layer.confirm('确认删除吗？', function(index){
                        $.post("{{ route('admin.footer.navmenus.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                            if (result.code==0){
                                obj.del(); //删除对应行（tr）的DOM结构
                            }
                            layer.close(index);
                            layer.msg(result.msg)
                        });
                    });
                } else if(_event === 'update'){
                    location.href = '/admin/footer-setting/navmenus/edit-'+data.id;
                } else if(_event === 'children'){
                    var pid = $("#returnParent").attr("pid");
                    if (data.parent_id!=0){
                        $("#returnParent").attr("pid",pid+'_'+data.parent_id);
                    }
                    navmenusTable.reload({
                        where:{parent_id:data.id},
                        page:{curr:1}
                    })
                }
            });

            // 监听单元格编辑
            table.on('edit(navmenusTable)', function(obj){
                var value = obj.value //得到修改后的值
                    ,data = obj.data //得到所在行所有键值
                    ,field = obj.field; //得到字段
                var num_reg = /^[0-9]{1,2}$/
                    ,linkuri = /(http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
                if(field == 'sortnum'){
                    if(!num_reg.test(value)){
                        layer.msg("请输入正确的排序编号！！",{icon:5});
                        navmenusTable.reload();
                        return false;
                    }
                }else if(field == 'linkuri'){
                    if(!linkuri.test(value)){
                        layer.msg("请输入正确的跳转地址！！",{icon:5});
                        navmenusTable.reload()
                        return false;
                    }
                }
                
                $.post('{{route('admin.footer.navmenus.change')}}',{_method:'put','id':data.id,'field':field,'value':value},function(result){
                    navmenusTable.reload()
                })
            });

            //批量删除
            $("#listDelete").click(function () {
                var ids = []
                var hasCheck = table.checkStatus('navmenusTable')
                var hasCheckData = hasCheck.data
                if (hasCheckData.length>0){
                    $.each(hasCheckData,function (index,element) {
                        ids.push(element.id)
                    })
                }
                if (ids.length>0){
                    layer.confirm('确认删除吗？', function(index){
                        $.post("{{ route('admin.footer.navmenus.destroy') }}",{_method:'delete',ids:ids},function (result) {
                            if (result.code==0){
                                navmenusTable.reload()
                            }
                            layer.close(index);
                            layer.msg(result.msg)
                        });
                    })
                }else {
                    layer.msg('请选择删除项')
                }
            });
            $("#returnParent").click(function () {
                var pid = $(this).attr("pid");
                if (pid!='0'){
                    ids = pid.split('_');
                    parent_id = ids.pop();
                    $(this).attr("pid",ids.join('_'));
                }else {
                    parent_id=pid;
                }
                navmenusTable.reload({
                    where:{parent_id:parent_id},
                    page:{curr:1}
                })
            })
        });
    </script>
@endsection