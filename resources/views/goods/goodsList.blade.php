@extends("layouts.bst")
@section('content')
    <h2 style="align-content: center;color: blue;">商品列表</h2>

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
    {{$list->links()}}
@endsection
