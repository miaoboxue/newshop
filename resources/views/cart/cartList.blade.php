{{-- 购物车 --}}
@extends('layouts.bst')

@section('content')
    <h2 style="color:blueviolet;">购物车列表</h2>
    <table class="table table-striped">
        <thead>
        <tr  class="info">
            <td>商品名称</td>
            <td>购买数量</td>
            <td>添加时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $v)
            <tr class="active">
                <td>{{$v['goods_name']}}</td>
                <td>{{$v['num']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td>
                    <button type="submit" class="btn  cart_del" goods_id="{{$v['goods_id']}}">删除</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a href="/orderAdd" id="submit_order" class="btn btn-danger btn-lg btn-block"> 提交订单 </a>
@endsection

@section('footer')
    @parent
    <script src="{{URL::asset('/js/goods/goods.js')}}"></script>
@endsection