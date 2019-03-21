@extends('layouts.bst')
@section('content')
<div class="nav navbar-right panel_toolbox col-xs-3">
    <form role="form" method="GET" action="/search">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="请输入标题" name="s" value="{{$search}}">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">搜索</button>
                </span>
        </div>
    </form>
</div>
    <table class="table table-striped">
        <thead>
        <tr class="active">
            <td>ID</td>
            <td>商品名称</td>
            <td>价格</td>
            <td>添加时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $v)
            <tr class="warning">
                <td>{{$v['goods_id']}}</td>
                <td>{{$v['goods_name']}}</td>
                <td>{{$v['price']/100}}</td>
                <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td><a href="/goods/{{$v['goods_id']}}">商品详情</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{$list->links()}}​
 @endsection


