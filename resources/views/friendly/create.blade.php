@extends('admin.base')
@section('content')
<div class="layui-row layui-col-space15">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加链接</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.footer.friendly.stored')}}" method="post">
                {{csrf_field()}}
                @include('footer-setting::friendly._form')
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
    
@endsection