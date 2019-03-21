@extends('layouts.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <input type="hidden" value="{{$curl}}" id="code_url">
    <input type="hidden" value="{{$order_sn}}" id="order_sn">

    <div id="code" align="center"></div>
    <div style="color: red;padding-left: 500px;padding-top: 50px;">请使用微信扫一扫付款</div>
@endsection
@section('footer')
@endsection
<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
<script>
    $(function(){
        var code_url=$('#code_url').val()
        console.log(code_url)
        $("#code").qrcode({
            render: "canvas", //table方式
            width: 200, //宽度
            height:200, //高度
            text:code_url //任意内容
        });
        var newmsg = function() {
            var order_sn=$('#order_sn').val()
            $.post(
                "/weixin/pay/payweixn",
                {order_sn:order_sn},
                function (msg) {
                    if(msg==1){
                        location.href='/weixin/pay/pay111';
                    }
                }
            )
        }


        //定时查询数据
        var s= setInterval(function(){
            newmsg();
        }, 1000*6)
    })

</script>

