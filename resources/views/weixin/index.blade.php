<div style="margin-left: 80px">
    <table>
        <tbody id="tbody">
        @foreach($msg as $v)
                <tr>
                    <td>{{$nickname}}：</td>
                    <td>{{$v['massage']}}</td>
                </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div style="float: right" id="right">

</div>
<form style="padding-top: 300px">/
    {{csrf_field()}}
    <textarea name="text"  cols="200" rows="5" id="text"></textarea>
    <input type="hidden" name="openid" id="openid" value="{{$openid}}">
    <input type="button" value="发送" id="sub">
</form>
<script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
<script>
    $(function () {
        $('#sub').click(function (msg) {
           var text=$('#text').val();
           var openid =$('#openid').val();
           $.post(
               "touser",
               {openid:openid,text:text},
                function (msg) {
                    if(msg=='发送成功'){
                        $('#right').append('<p>'+text+':客服</p>');
                        $('#text').val('');
                    }
                }

           )
        })
        function newmsg() {
            var openid =$('#openid').val();
            var _tr = "";
            $.post(
                "message",
                {openid:openid},
                function (msg) {
                    for(var i in msg['data']) {
                        _tr +="<tr>"+"<td>"+msg['nickname']+"：</td>" +
                            "<td>"+msg['data'][i]['massage']+"</td>" +
                            "</tr>"
                    }
                    //console.log(_tr);
                    //替换数据
                    $('#tbody').html(_tr)
                },
                'json'
            );
        }
        //定时查询数据
        var s= setInterval(function(){
            newmsg();
        }, 1000*3)
    })
</script>
