<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\OrderModel;

class PayController extends Controller
{
    //
    public function index(){

    }
    //订单支付
    public function orderPay($oid){
        //查询订单
        $orderInfo=OrderModel::where(['order_id'=>$oid])->first();
        if(!$orderInfo){
            die('订单'.$oid.'不存在');
        }
        //检查订单状态 是否已支付 已过期 已删除
        if($orderInfo->pay_time > 0){
            header('Refresh:2;url=/orderList');
            die("此订单已被支付，无法再次支付");
        }

        //调起支付宝支付


        //支付成功 修改支付时间
        OrderModel::where(['order_id'=>$oid])->update(['pay_time'=>time(),'pay_amount'=>rand(1111,9999),'is_pay'=>1]);

        //增加消费积分 ...

        header('Refresh:2;url=/orderList');
        echo '支付成功，正在跳转';

    }
}
