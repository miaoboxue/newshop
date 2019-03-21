/*加入购物车*/
$("#add_cart_btn").click(function(e){
    e.preventDefault();
    var num = $("#goods_num").val();
    var goods_id = $("#goods_id").val();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url     :   '/add2',
        type    :   'post',
        data    :   {goods_id:goods_id,num:num},
        dataType:   'json',
        success :   function(d){
            if(d.error==301){
                window.location.href=d.url;
            }else{
                alert(d.msg);
                window.location.href="/cart";
            }
        }
    });
});
//删除购物车商品
$('.cart_del').click(function(e){
    //限制表单提交
    e.preventDefault();
    var _this=$(this);
    var goods_id=_this.attr('goods_id');
    //console.log(goods_id);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:'/cartDel2',
        type:'post',
        data:{goods_id:goods_id},
        datatype:'json',
        success :   function(d){
            if(d.error==301){
                window.location.href=d.url;
            }else{
                alert(d.msg);
                window.location.href="/cart";
            }
        }
    });
});
