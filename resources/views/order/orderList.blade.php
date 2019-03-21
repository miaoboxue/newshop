@extends('layouts.bst')
@section('content')
    <h2 style="color:blue;">订单列表</h2>
    <table class="table table-striped">
        <thead>
        <tr class="info">
            <td>ID</td>
            <td>订单号</td>
            <td>总金额</td>
            <td>订单时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $v)
            <tr calss="info">
                <td>{{$v['order_id']}}</td>
                <td>{{$v['order_sn']}}</td>
                <td>{{$v['add_amount']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td>
                    <?php if($v['is_pay']==0){?>
                        <a href="/orderPay/{{$v['order_id']}}" class="btn btn-info"> 去支付 </a>
                        <a href="/weixin/pay/test/{{$v['order_sn']}}" class="btn btn-info"> 微信支付 </a>
                    <?php }else{ ?>
                        <a href="#" class="btn btn-info"> 已支付 </a>
                    <?php }?>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection