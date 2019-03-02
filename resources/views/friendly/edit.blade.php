@extends('admin.base')
@section('content')
<div class="layui-row layui-col-space15">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>编辑【{{$friendly->title}}】</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.footer.friendly.update')}}" method="post">
                {{csrf_field()}}
                {{ method_field('put') }}
                <input type="hidden" name="id" value="{{$friendly->id}}" />
                @include('footer-setting::friendly._form')
            </form>
        </div>
    </div>
</div>
@endsection