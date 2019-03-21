<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use App\Model\CartModel;
use App\Model\OrderModel;

class OrderController extends Controller
{
    //
    public function index(){
        echo __METHOD__;
    }


    public function __construct(){
        $this->Middleware('auth');
    }
    //下单
     public function orderAdd(Request $request){
        //查询购物车商品
        $cart_goods = CartModel::where(['uid'=>session()->get('uid')])->orderBy('id','desc')->get()->toArray();
        if(empty($cart_goods)){
            die("购物车中无商品");
        }
        $order_amount=0;
        foreach($cart_goods as $v){
            $goodsInfo = GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
            $goodsInfo['num'] = $v['num'];
            $list[] = $goodsInfo;
            //计算订单价格=单价*数量
            $order_amount+=$goodsInfo['price']*$v['num'];
        }
        //生成订单号
        $order_sn=OrderModel::generateOrderSN();
        $data=[
            'order_sn'=>$order_sn,
            'uid'=>session()->get('uid'),
            'add_time'=>time(),
            'add_amount'=>$order_amount
        ];
        $oid=OrderModel::insertGetId($data);
        if(!$oid){
            echo "生成订单失败";
        }
        header('Refresh:2;url=/orderList');
        echo "订单生成,跳转支付!";

        //清空购物车
        CartModel::where(['uid'=>session()->get('uid')])->delete();
    }
    public function orderList(){
        $list=OrderModel::all()->toArray();
        $data=[
            'list'=>$list
        ];
        return view('order.orderList',$data);
    }
}
