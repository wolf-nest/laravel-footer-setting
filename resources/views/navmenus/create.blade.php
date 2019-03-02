@extends('admin.base')
@section('content')
<div class="layui-row layui-col-space15">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加菜单</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.footer.navmenus.stored')}}" method="post">
                {{csrf_field()}}
                @include('footer-setting::navmenus._form')
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
    
@endsection